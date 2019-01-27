<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Service\PermissionService;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class CreatePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zone:create-permissions {--g|guard=Admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create permissions';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $permission = new PermissionService();

        $permission->index();

        switch (strtoupper($this->option('guard'))) {
            case 'ADMIN':
                $permissions = $permission->getPermissions('Admin');
                break;
            case 'CLEAN':
                return $this->clean();
                break;
        }

        $this->save($permissions);
    }

    /**
     *
     */
    public function clean()
    {
        if ($this->confirm('此操作会清空角色权限表和权限表,确定继续?')) {

            DB::table('role_has_permissions')->truncate();
            DB::table('permissions')->truncate();

            //清空权限缓存
            app()['cache']->forget('spatie.permission.cache');

            $this->info('权限清空完成!');
        } else {
            $this->info('操作取消!');
        }
    }

    public function save($permissions)
    {
        $bar = $this->output->createProgressBar(count($permissions));

        $flag = 0;

        $where = [];

        $guard_name = 'web';

        foreach ($permissions as $item) {
            $where[1] = [
                'name',
                $item,
            ];

            try {
                $permission = Permission::where($where)->firstOrFail();

            } catch (\Exception $e) {
                $data = [
                    'name' => $item
                ];

                Permission::create($data);

                $flag++;
            }

            $bar->advance();
        }

        $bar->finish();

        $this->info('');
        $this->info($guard_name.' 权限创建完成');
        $this->info('新增权限 '.$flag.'个');
    }
}
