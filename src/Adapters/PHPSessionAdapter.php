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

class PHPSessionAdapter implements SessionInterface
{
    /**
     * @var string
     */
    private $session_id;
    /**
     * @var array
     */
    private $dados = [];

    /**
     * @return string|null
     */
    public function getSessionId(): ?string
    {
        return $this->session_id;
    }

    /**
     * Alterar o ID da sessão atual
     * @param string $session_id
     * @return PHPSessionAdapter
     */
    public function setSessionId(string $session_id): SessionInterface
    {
        if (!$this->isAtiva()) {
            session_id($session_id);
            $this->session_id = $session_id;
        }

        return $this;
    }

    /**
     * PHPSessionAdapter constructor.
     * @param string|null $session_id
     */
    public function __construct(?string $session_id = null)
    {
        if (!empty($session_id)) {
            $this->setSessionId($session_id);
        }

        $this->start();
        $this->dados = &$_SESSION;
    }

    /**
     * @param string $attr
     * @return mixed
     */
    public function get(string $attr)
    {
        return $this->has($attr) ? $this->dados[$attr] : null;
    }

    /**
     * @param string $attr
     * @param $valor
     * @return SessionInterface
     */
    public function set(string $attr, $valor): SessionInterface
    {
        $this->dados[$attr] = $valor;
        return $this;
    }

    /**
     * @param string $attr
     * @return bool
     */
    public function has(string $attr): bool
    {
        return array_key_exists($attr, $this->dados);
    }

    /**
     * @param string $attr
     * @return SessionInterface
     */
    public function unset(string $attr): SessionInterface
    {
        if ($this->has($attr)) {
            unset($this->dados[$attr]);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isAtiva(): bool
    {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * @return bool
     */
    public function destroy(): bool
    {
        return session_destroy();
    }

    /**
     * Iniciar uma sessão
     * @return bool
     */
    public function start(): bool
    {
        if (!$this->isAtiva()) {
            if (headers_sent()) {
                return false;
            }

            session_start();
        }

        $this->setSessionId(session_id());

        return true;
    }
}