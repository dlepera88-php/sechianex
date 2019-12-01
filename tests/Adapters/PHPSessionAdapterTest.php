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

namespace Tests\Adapters;

use PHPUnit\Framework\TestCase;
use SechianeX\Adapters\PHPSessionAdapter;
use SechianeX\Contracts\SessionInterface;
use SechianeX\Exceptions\SessionAdapterInterfaceInvalidaException;
use SechianeX\Exceptions\SessionAdapterNaoEncontradoException;
use SechianeX\Factories\SessionFactory;

$_SESSION = [];

class PHPSessionAdapterTest extends TestCase
{
    /** @var SessionInterface */
    private $session;

    protected function setUp()
    {
        parent::setUp();
        $this->session = new PHPSessionAdapter('sessao-teste');
    }


    public function test_Get_Set()
    {
        global $_SESSION;

        $valor = 'valor do teste';
        $this->session->set('teste', $valor);

        $this->assertEquals($valor, $this->session->get('teste'));
    }

    /**
     * @depends test_Get_Set
     */
    public function test_Unset()
    {
        global $_SESSION;

        $this->session->unset('teste');
        $this->assertFalse($this->session->has('teste'));
    }

    public function test_GetSessionId()
    {
        $this->assertEquals('sessao-teste', $this->session->getSessionId());
    }

    /**
     * @throws SessionAdapterInterfaceInvalidaException
     * @throws SessionAdapterNaoEncontradoException
     */
    public function test_create_PHPSession_sem_especificar_id()
    {
        $this->markTestSkipped('Não dá para testar sessões com o PHP Unit :(');

        $session = SessionFactory::createPHPSession();
        $this->assertEquals('sessao-teste', $session->getSessionId());
    }
}
