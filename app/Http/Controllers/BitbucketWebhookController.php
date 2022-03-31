<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;
use Symfony\Component\Process\Process;

class BitbucketWebhookController extends Controller
{
	public function __invoke(Request $request)
	{
		$key = config('static.deploy_secret');

		if($request->key === $key)
		{
			$root_path = base_path();
			$process = new Process([
				'./pull.sh',		
			]);
			$process->setWorkingDirectory($root_path);
			$process->run(function ($type, $buffer) {
				echo $buffer;
			});
		} else {
			return response()->json([
				'error' => true,
				'message' => 'invalid key'
			], 403);
		}

		//testing webhook 
	}
}
