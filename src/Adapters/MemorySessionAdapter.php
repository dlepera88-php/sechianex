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

namespace SechianeX\Adapters;


use SechianeX\Contracts\SessionInterface;

class MemorySessionAdapter implements SessionInterface
{
    /**
     * @var string
     */
    private $session_id;
    /**
     * @var array
     */
    private $attr = [];

    /**
     * MemorySessionAdapter constructor.
     * @param string $session_id
     */
    public function __construct(string $session_id)
    {
        $this->setSessionId($session_id);
    }

    /**
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->session_id;
    }

    /**
     * @param string $session_id
     * @return SessionInterface
     */
    public function setSessionId(string $session_id): SessionInterface
    {
        $this->session_id = $session_id;

        if (!array_key_exists($session_id, $this->attr)) {
            $this->start();
        }

        return $this;
    }

    /**
     * @param string $attr
     * @return mixed
     */
    public function get(string $attr)
    {
        return $this->has($attr) ? $this->attr[$this->getSessionId()][$attr] : null;
    }

    /**
     * @param string $attr
     * @param $valor
     * @return SessionInterface
     */
    public function set(string $attr, $valor): SessionInterface
    {
        $this->attr[$this->getSessionId()][$attr] = $valor;
        return $this;
    }

    /**
     * @param string $attr
     * @return bool
     */
    public function has(string $attr): bool
    {
        return array_key_exists($attr, $this->attr[$this->getSessionId()]);
    }

    /**
     * @param string $attr
     * @return SessionInterface
     */
    public function unset(string $attr): SessionInterface
    {
        if ($this->has($attr)) {
            unset($this->attr[$this->getSessionId()][$attr]);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        unset($this->attr[$this->getSessionId()]);
        return array_key_exists($this->getSessionId(), $this->attr);
    }

    /**
     * Iniciar uma sessão
     * @return bool
     */
    public function start(): bool
    {
        $this->attr[$this->session_id] = [];
        return true;
    }

    /**
     * Verifica se a sessão está ativa
     * @return bool
     */
    public function isAtiva(): bool
    {
        return array_key_exists($this->getSessionId(), $this->attr);
    }
}