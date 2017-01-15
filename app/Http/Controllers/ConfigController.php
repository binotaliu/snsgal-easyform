<?php

namespace App\Http\Controllers;

use App\Repositories\ConfigRepository;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    public function index()
    {
        return $this->configRepository->getConfigs();
    }

    public function store(Request $request)
    {
        foreach ($request->get('configs') as $key => $value) {
            $this->configRepository->updateConfig($key, $value);
        }
    }
}
