<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'kanda.read']);
        Permission::create(['name' => 'kanda.create']);
        Permission::create(['name' => 'kanda.update']);
        Permission::create(['name' => 'kanda.delete']);

        Permission::create(['name' => 'jumuiya.read']);
        Permission::create(['name' => 'jumuiya.create']);
        Permission::create(['name' => 'jumuiya.update']);
        Permission::create(['name' => 'jumuiya.delete']);

        Permission::create(['name' => 'vyama_vya_kitume.read']);
        Permission::create(['name' => 'vyama_vya_kitume.create']);
        Permission::create(['name' => 'vyama_vya_kitume.update']);
        Permission::create(['name' => 'vyama_vya_kitume.delete']);

        Permission::create(['name' => 'masakramenti.read']);
        Permission::create(['name' => 'masakramenti.create']);
        Permission::create(['name' => 'masakramenti.update']);
        Permission::create(['name' => 'masakramenti.delete']);

        Permission::create(['name' => 'mafundisho.read']);
        Permission::create(['name' => 'mafundisho.create']);
        Permission::create(['name' => 'mafundisho.update']);
        Permission::create(['name' => 'mafundisho.delete']);

        Permission::create(['name' => 'sadaka_za_misa.read']);
        Permission::create(['name' => 'sadaka_za_misa.create']);
        Permission::create(['name' => 'sadaka_za_misa.update']);
        Permission::create(['name' => 'sadaka_za_misa.delete']);

        Permission::create(['name' => 'sadaka_za_jumuiya.read']);
        Permission::create(['name' => 'sadaka_za_jumuiya.create']);
        Permission::create(['name' => 'sadaka_za_jumuiya.update']);
        Permission::create(['name' => 'sadaka_za_jumuiya.delete']);

        Permission::create(['name' => 'zaka.read']);
        Permission::create(['name' => 'zaka.create']);
        Permission::create(['name' => 'zaka.update']);
        Permission::create(['name' => 'zaka.delete']);

        Permission::create(['name' => 'michango.read']);
        Permission::create(['name' => 'michango.create']);
        Permission::create(['name' => 'michango.update']);
        Permission::create(['name' => 'michango.delete']);

        Permission::create(['name' => 'mapato_matumizi.read']);
        Permission::create(['name' => 'mapato_matumizi.create']);
        Permission::create(['name' => 'mapato_matumizi.update']);
        Permission::create(['name' => 'mapato_matumizi.delete']);

        Permission::create(['name' => 'bajeti.read']);
        Permission::create(['name' => 'bajeti.create']);
        Permission::create(['name' => 'bajeti.update']);
        Permission::create(['name' => 'bajeti.delete']);

        Permission::create(['name' => 'report_masakramenti.read']);
        Permission::create(['name' => 'report_masakramenti.create']);
        Permission::create(['name' => 'report_masakramenti.update']);
        Permission::create(['name' => 'report_masakramenti.delete']);

        Permission::create(['name' => 'report_fedha.read']);
        Permission::create(['name' => 'report_fedha.create']);
        Permission::create(['name' => 'report_fedha.update']);
        Permission::create(['name' => 'report_fedha.delete']);

        Permission::create(['name' => 'report_zinginezo.read']);
        Permission::create(['name' => 'report_zinginezo.create']);
        Permission::create(['name' => 'report_zinginezo.update']);
        Permission::create(['name' => 'report_zinginezo.delete']);

        Permission::create(['name' => 'viongozi.read']);
        Permission::create(['name' => 'viongozi.create']);
        Permission::create(['name' => 'viongozi.update']);
        Permission::create(['name' => 'viongozi.delete']);

        Permission::create(['name' => 'mengineyo.read']);
        Permission::create(['name' => 'mengineyo.create']);
        Permission::create(['name' => 'mengineyo.update']);
        Permission::create(['name' => 'mengineyo.delete']);

        Permission::create(['name' => 'tovuti.read']);
        Permission::create(['name' => 'tovuti.create']);
        Permission::create(['name' => 'tovuti.update']);
        Permission::create(['name' => 'tovuti.delete']);

        Permission::create(['name' => 'mfumo.read']);
        Permission::create(['name' => 'mfumo.create']);
        Permission::create(['name' => 'mfumo.update']);
        Permission::create(['name' => 'mfumo.delete']);

        $role = Role::create(['name' => 'administrator']);
        $role->givePermissionTo(Permission::all());
    }
}
