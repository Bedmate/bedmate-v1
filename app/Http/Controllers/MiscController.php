<?php

namespace App\Http\Controllers;

use App\Services\RekognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MiscController extends Controller
{
    protected $rekognitionService;

    public function __construct(RekognitionService $rekognitionService)
    {
        $this->rekognitionService = $rekognitionService;
    }

    public function compare(Request $request)
    {
        //     $request->validate([
        //         'image_1' => 'required',
        //         'image_2' => 'required'
        //     ]);

        //     $image1 = $request->file('image_1');
        //     $image2 = $request->file('image_2');
        //     // $result =  compare_image($image1, $image2);
        //     $result = $image1->compareImages($image2, Imagick::METRIC_MEANSQUAREERROR);
        //     return response()->json($result);
    }

    public function verifyImages()
    {
        try {
            $compare = $this->rekognitionService->matchFaces(request());
            return response()->json($compare);
        } catch (\Throwable $th) {
            return get_error_response(['error' => $th->getMessage()]);
        }
    }



    public function compareImages(Request $request)
    {
        try {
            $selfie = $request->file('selfie');
            $images = $request->file('images');

            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => '12c6a118a3994715bd4034e1b9fdc4c8',
                'Content-Type' => 'application/json'
            ])->post('https://face-api-cumrid.cognitiveservices.azure.com/compare', [
                'selfie' => base64_encode(file_get_contents($selfie)),
                'images' => array_map(function ($image) {
                    return base64_encode(file_get_contents($image));
                }, $images),
            ]);

            return $response->json();

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }


}
