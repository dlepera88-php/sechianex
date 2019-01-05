<?php
/**
 * MIT License
 *
 * Copyright (c) 2018 PHP DLX
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace SechianeX\Factories;


use SechianeX\Adapters\MemorySessionAdapter;
use SechianeX\Adapters\PHPSessionAdapter;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;

class SessionFactory
{
    /**
     * @param string $adapter
     * @param string|null $session_id
     * @return SessionInterface
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    public static function createFromAdapter(string $adapter, ?string $session_id = null): SessionInterface
    {
        if (!class_exists($adapter)) {
            throw new SessionAdapterNaoEncontradoException($adapter);
        }

        if (!in_array(SessionInterface::class, class_implements($adapter))) {
            throw new SessionAdapterInterfaceInvalidaException($adapter);
        }

        return new $adapter($session_id);
    }

    /**
     * @param string|null $session_id
     * @return PHPSessionAdapter
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    public static function createPHPSession(?string $session_id = null): PHPSessionAdapter
    {
        /** @var PHPSessionAdapter $adapter */
        $adapter = SessionFactory::createFromAdapter(PHPSessionAdapter::class, $session_id);
        return $adapter;
    }

    /**
     * @param string|null $session_id
     * @return MemorySessionAdapter
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    public static function createMemorySession(?string $session_id = null): MemorySessionAdapter
    {
        /** @var MemorySessionAdapter $adapter */
        $adapter = SessionFactory::createFromAdapter(MemorySessionAdapter::class, $session_id);
        return $adapter;
    }
}