<?php

declare(strict_types=1);

namespace Aybarsm\Laravel\Support\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Fluent;

final class AsEncryptedFluent implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return fluent(Json::decode(Crypt::decryptString($value)));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        /** @var Fluent $value */
        return Crypt::encryptString(Json::encode($value->all()));
    }
}
