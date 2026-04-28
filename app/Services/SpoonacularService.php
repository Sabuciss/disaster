<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SpoonacularService
{
    protected string $apiBaseUrl;
    protected array $apiKeys;

    public function __construct()
    {
        $configuredKeys = array_filter([
            config('services.spoonacular.key'),
            config('services.spoonacular.key2'),
            config('services.spoonacular.key3'),
            config('services.spoonacular.key4'),
        ]);
        $this->apiKeys = array_values($configuredKeys);
        $this->apiBaseUrl = config('services.spoonacular.base_uri', 'https://api.spoonacular.com');
    }

    public function complexSearch(array $searchParams = []): array
    {
        return $this->requestJson('/recipes/complexSearch', $searchParams);
    }

    public function information(string $recipeId, array $additionalParams = []): array
    {
        return $this->requestJson("/recipes/{$recipeId}/information", $additionalParams);
    }

    public function random(array $randomParams = []): array
    {
        return $this->requestJson('/recipes/random', $randomParams);
    }

    public function analyzedInstructions($recipeId, bool $includeStepBreakdown = true): array
    {
        $requestParams = [];
        if ($includeStepBreakdown) {
            $requestParams['stepBreakdown'] = 'true';
        }
        return $this->requestJson("/recipes/{$recipeId}/analyzedInstructions", $requestParams);
    }

    public function findByIngredients(string $ingredientsList, int $maxResults = 5): array
    {
        $searchParams = ['ingredients' => $ingredientsList, 'number' => $maxResults];
        return $this->requestJson('/recipes/findByIngredients', $searchParams);
    }

    protected function requestJson(string $apiEndpointPath, array $requestParams = []): array
    {
        $availableKeys = $this->apiKeys ?: [null];
        $lastHttpResponse = null;

        foreach ($availableKeys as $apiKey) {
            $finalParams = $requestParams;
            if ($apiKey) $finalParams['apiKey'] = $apiKey;
            $httpResponse = Http::get($this->apiBaseUrl . $apiEndpointPath, $finalParams);
            $lastHttpResponse = $httpResponse;
            if (in_array($httpResponse->status(), [402, 429], true)) {
                continue;
            }
            $httpResponse->throw();
            return $httpResponse->json();
        }

        if ($lastHttpResponse) $lastHttpResponse->throw();
        return [];
    }
}
