<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.0.0
 */
namespace App\Test\Factory;

use App\Model\Entity\Role;
use Cake\Chronos\Chronos;
use CakephpFixtureFactories\Factory\BaseFactory as CakephpBaseFactory;
use Faker\Generator;
use Passbolt\Log\Test\Factory\ActionLogFactory;

/**
 * UserFactory
 */
class UserFactory extends CakephpBaseFactory
{
    /**
     * Defines the Table Registry used to generate entities with
     *
     * @return string
     */
    protected function getRootTableRegistryName(): string
    {
        return 'Users';
    }

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        $this->setDefaultData(function (Generator $faker) {
            return [
                'username' => $faker->userName . '@passbolt.com',
                'active' => true,
                'deleted' => false,
                'created' => Chronos::now()->subDay($faker->randomNumber(4)),
                'modified' => Chronos::now()->subDay($faker->randomNumber(4)),
            ];
        });

        $this
            ->with('Roles')
            ->with('Profiles')
            ->with('Gpgkeys')
            ->with('GroupsUsers');
    }

    /**
     * @param string $name
     * @return $this
     */
    public function withRole(string $name)
    {
        return $this->with('Roles', compact('name'));
    }

    /**
     * @return $this
     */
    public function admin()
    {
        return $this->withRole(Role::ADMIN);
    }

    /**
     * @return $this
     */
    public function user()
    {
        return $this->withRole(Role::USER);
    }

    /**
     * @return $this
     */
    public function guest()
    {
        return $this->withRole(Role::GUEST);
    }

    /**
     * @return $this
     */
    public function inactive()
    {
        return $this->patchData(['active' => false]);
    }

    /**
     * @return $this
     */
    public function active()
    {
        return $this->patchData(['active' => true]);
    }

    /**
     * @param int $n
     * @return self
     */
    public function withLogIn(int $n = 1): self
    {
        return $this->with('ActionLogs', ActionLogFactory::make($n)->loginAction());
    }
}