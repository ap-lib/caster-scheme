<?php declare(strict_types=1);

namespace AP\Caster\Scheme;

use AP\Caster\CasterInterface;
use AP\Context\Context;
use AP\ErrorNode\Error;
use AP\ErrorNode\ThrowableErrors;
use AP\Scheme\ToObject;
use AP\Scheme\ToObjectFromDatabase;

class SchemeCasterDatabase implements CasterInterface
{
    /**
     * Attempts to cast a given value to the expected type.
     *
     * Implementations should:
     *  - Return `false` to skip casting.
     *  - Return `true` if casting was successful.
     *  - Return a non-empty array of `AP\ErrorNode\Error` instances if casting failed.
     *
     * @param string $expected The expected final type, see: `get_debug_type()`
     * @param mixed    &$el The value to be cast, passed by reference
     * @param ?Context $context Context object providing metadata and settings for casting
     *
     * @return bool|array<Error> `false` to skip, `true` if successfully casted,
     *                               or a non-empty array of errors if casting failed.
     * @throws \Throwable If a fatal error occurs, that's unrelated to input validation.
     */
    public function cast(
        string   $expected,
        mixed    &$el,
        ?Context $context = null,
    ): bool|array
    {
        if (class_exists($expected) && is_subclass_of($expected, ToObjectFromDatabase::class)) {
            try {
                $el = ($expected)::toObjectFromDatabase($el);
                return true;
            } catch (ThrowableErrors) {
            }
        }
        if (class_exists($expected) && is_subclass_of($expected, ToObject::class)) {
            try {
                $el = ($expected)::toObject($el);
                return true;
            } catch (ThrowableErrors) {
            }
        }
        return false;
    }
}