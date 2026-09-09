<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Cliente mínimo para la Generative Language API de Gemini (plan gratuito).
 *
 * Dos usos, los dos siempre devuelven datos (nunca HTML) y nunca escriben
 * directo a la base -- eso lo valida y persiste siempre el backend (ver
 * App\Services\NegocioWriter): `extraer()` convierte un mensaje en lenguaje
 * natural del usuario en un JSON con campos fijos, `generar()` (Fase 5,
 * Paso 5) redacta un texto sugerido a partir de unos pocos datos de
 * contexto -- en los dos casos el usuario tiene la última palabra antes de
 * que se guarde nada.
 */
class GeminiClient
{
    private const URL = 'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent';

    private $http;
    private $apiKey;
    private $modelo;

    public function __construct()
    {
        $this->http = new Client(['timeout' => 15]);
        $this->apiKey = config('services.gemini.key') ?: null;
        $this->modelo = config('services.gemini.model', 'gemini-2.0-flash');
    }

    public function disponible(): bool
    {
        return ! empty($this->apiKey);
    }

    /**
     * @param string $instrucciones qué extraer del mensaje, en español, en texto libre
     * @param string $mensajeUsuario lo que escribió el usuario en el chat
     * @param array<string,string> $schema nombre de campo => tipo ('string'|'integer'|'number')
     * @return array<string,mixed>|null null si Gemini no está disponible o falló (el llamador debe hacer fallback)
     */
    public function extraer(string $instrucciones, string $mensajeUsuario, array $schema): ?array
    {
        if (! $this->disponible()) {
            return null;
        }

        $tipos = ['string' => 'STRING', 'integer' => 'INTEGER', 'number' => 'NUMBER', 'boolean' => 'BOOLEAN'];

        $properties = [];
        foreach ($schema as $campo => $tipo) {
            $properties[$campo] = ['type' => $tipos[$tipo] ?? 'STRING'];
        }

        $prompt = $instrucciones."\n\n"
            .'Si un dato no aparece en el mensaje, devolvé ese campo como null o cadena vacía, no inventes información.'
            ."\n\nMensaje del usuario: \"{$mensajeUsuario}\"";

        $schemaRespuesta = ['type' => 'OBJECT', 'properties' => $properties];

        return $this->generarJson($prompt, $schemaRespuesta);
    }

    /**
     * Redacta un texto corto sugerido (ej. la descripción de un negocio)
     * a partir de un contexto de unos pocos datos ya conocidos -- nunca
     * inventa datos que no estén en ese contexto. Devuelve texto plano
     * (o null si Gemini no está disponible o falló), no HTML ni Markdown.
     *
     * @param string $instrucciones qué redactar y con qué tono, en español, en texto libre
     * @param string $contexto los datos del negocio que ya se conocen (nombre, rubro, etc.)
     */
    public function generar(string $instrucciones, string $contexto): ?string
    {
        if (! $this->disponible()) {
            return null;
        }

        $prompt = $instrucciones."\n\n"
            .'No inventes datos que no estén en el contexto de abajo. Nunca uses HTML ni Markdown, solo texto plano.'
            ."\n\nContexto: \"{$contexto}\"";

        $schemaRespuesta = ['type' => 'OBJECT', 'properties' => ['texto' => ['type' => 'STRING']]];

        $resultado = $this->generarJson($prompt, $schemaRespuesta);

        return $resultado['texto'] ?? null;
    }

    /**
     * Único punto de contacto HTTP real con la API -- `extraer()` y
     * `generar()` solo difieren en el prompt y el schema que le piden.
     */
    private function generarJson(string $prompt, array $schemaRespuesta): ?array
    {
        $body = [
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $prompt]]],
            ],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'responseSchema' => $schemaRespuesta,
            ],
        ];

        try {
            $response = $this->http->post(sprintf(self::URL, $this->modelo), [
                'query' => ['key' => $this->apiKey],
                'json' => $body,
            ]);

            $data = json_decode((string) $response->getBody(), true);
            $texto = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;

            if (! $texto) {
                return null;
            }

            $decodificado = json_decode($texto, true);

            return is_array($decodificado) ? $decodificado : null;
        } catch (\Throwable $e) {
            Log::warning('Gemini no disponible, se usa fallback manual: '.$e->getMessage());

            return null;
        }
    }
}
