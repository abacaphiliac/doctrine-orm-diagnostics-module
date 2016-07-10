<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */
return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'default_repository_class_name' => \DoctrineORMModuleTest\Assets\RepositoryClass::class,
            ],
        ],
        'driver' => [
            'Abacaphiliac\DoctrineORMDiagnosticsModuleTest\Assets\Entity' => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../Assets/Entity',
                ],
            ],
            'orm_default' => [
                'drivers' => [
                    'Abacaphiliac\DoctrineORMDiagnosticsModuleTest\Assets\Entity' =>
                        'Abacaphiliac\DoctrineORMDiagnosticsModuleTest\Assets\Entity',
                ],
            ],
        ],
        'entity_resolver' => [
            'orm_default' => [
                'resolvers' => [
                    \DoctrineORMModuleTest\Assets\Entity\TargetInterface::class
                        => \DoctrineORMModuleTest\Assets\Entity\TargetEntity::class,
                ],
            ],
        ],
        'connection' => [
            'orm_default' => [
                'configuration' => 'orm_default',
                'eventmanager'  => 'orm_default',
                'driverClass'   => \Doctrine\DBAL\Driver\PDOSqlite\Driver::class,
                'params' => [
                    'memory' => true,
                ],
            ],
        ],
        'migrations_configuration' => [
            'orm_default' => [
                'directory' => __DIR__,
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'zftool-diagnostics' => [
                'type'  => \Zend\Mvc\Router\Http\Literal::class,
                'options' => [
                    'route' => '/diagnostics',
                    'defaults' => [
                        'controller' => 'ZFTool\Controller\Diagnostics',
                        'action'     => 'run'
                    ],
                ],
            ],
        ],
    ],

    'view_manager' => [
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    
    //'diagnostics' => [
    //    'DoctrineORMDiagnosticsModule' => [
    //        'ORM Schema Valid' => 'doctrine.orm_diagnostics.schema',
    //        'Schema Migrations Up-To-Date' => 'doctrine.migrations_diagnostics.uptodate',
    //    ],
    //],
];