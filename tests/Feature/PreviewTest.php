<?php
/**
 * Invoice Ninja (https://invoiceninja.com).
 *
 * @link https://github.com/invoiceninja/invoiceninja source repository
 *
 * @copyright Copyright (c) 2021. Invoice Ninja LLC (https://invoiceninja.com)
 *
 * @license https://opensource.org/licenses/AAL
 */
namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Tests\MockAccountData;
use Tests\TestCase;

/**
 * @test
 * @covers App\Http\Controllers\PreviewController
 */
class PreviewTest extends TestCase
{
    use DatabaseTransactions;
    use MockAccountData;

    public function setUp() :void
    {
        parent::setUp();

        $this->makeTestData();

        $this->withoutMiddleware(
            ThrottleRequests::class
        );
    }

    public function testPreviewRoute()
    {
    	$data = $this->getData();

    	 $response = $this->withHeaders([
                'X-API-SECRET' => config('ninja.api_secret'),
                'X-API-TOKEN' => $this->token,
            ])->post('/api/v1/preview/', $data);

         $response->assertStatus(200);
    }

    public function testPreviewHtmlResponse()
    {
    	$data = $this->getData();

    	 $response = $this->withHeaders([
                'X-API-SECRET' => config('ninja.api_secret'),
                'X-API-TOKEN' => $this->token,
            ])->post('/api/v1/preview?html=true', $data);

         $response->assertStatus(200);

    }


    private function getData()
    {
    	$data =
    		[
    	    'entity_type' => 'invoice',
		    'entity_id' => '', 
		    'design' => [
		            'name' => '', 
		            'design' => [
                    'includes' => '</style>',
                    'header' => '<div id="header"></div>',
                    'body' => '<div id="body">',
                    'product' => '',
                    'task' => '',
                    'footer' => '<div id="footer">$entity_footer</div>'
                   	],
		        ],
            'is_custom' => 1,
        ];

        return $data;
    }
}