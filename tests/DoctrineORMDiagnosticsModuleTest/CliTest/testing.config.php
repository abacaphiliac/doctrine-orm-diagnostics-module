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
return array(
    'doctrine' => array(
        'configuration' => array(
            'orm_default' => array(
                'default_repository_class_name' => \DoctrineORMModuleTest\Assets\RepositoryClass::class,
            ),
        ),
        'driver' => array(
            'DoctrineORMModuleTest\Assets\Entity' => array(
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../../../vendor/doctrine/doctrine-orm-module/tests/DoctrineORMModuleTest/Assets/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    'DoctrineORMModuleTest\Assets\Entity' => 'DoctrineORMModuleTest\Assets\Entity',
                ),
            ),
        ),
        'entity_resolver' => array(
            'orm_default' => array(
                'resolvers' => array(
                    \DoctrineORMModuleTest\Assets\Entity\TargetInterface::class
                        => \DoctrineORMModuleTest\Assets\Entity\TargetEntity::class,
                ),
            ),
        ),
        'connection' => array(
            'orm_default' => array(
                'configuration' => 'orm_default',
                'eventmanager'  => 'orm_default',
                'driverClass'   => \Doctrine\DBAL\Driver\PDOSqlite\Driver::class,
                'params' => array(
                    'memory' => true,
                ),
            ),
        ),
        'migrations_configuration' => array(
            'orm_default' => array(
                'directory' => __DIR__,
            ),
        ),
    ),
);