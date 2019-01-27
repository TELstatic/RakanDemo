<?php

if (!function_exists('reply')) {
    function reply($bool = false, $msg = '')
    {
        return response()->json([
            'code' => $bool ? 200 : 500,
            'msg'  => $msg
        ]);
    }
}

if (!function_exists('answer')) {
    function answer($bool = false, $msg = '', $data = null)
    {
        return response()->json([
            'success' => $bool,
            'desc'    => $msg,
            'result'  => $data
        ]);
    }
}
