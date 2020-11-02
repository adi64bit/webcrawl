<?php
namespace App\Library;
use Illuminate\Http\Response;
use \Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use \PhpInsights\InsightsCaller;
//AIzaSyAcKhHJfOIT5NQYYU0ACC5I_yqu2GdkCXE
class GooglePageSpeed
{
  //Array to store raw result from API request
  public $rawResult = array();

  //Array to store final result after parsing raw result
  public $finalResult = array();

  protected $url;

  protected $folder_name;

  protected $time;

  public function __construct($url, $folder_name, $time, $strategy)
  {
    $this->getResults($url, $strategy);
    $this->url = $url;
    $this->folder_name = $folder_name;
    $this->time = $time;
  }

  //Get all results from google page speed and store in array
  protected function getResults($url, $strategy)
  {
    $caller = new InsightsCaller('AIzaSyAcKhHJfOIT5NQYYU0ACC5I_yqu2GdkCXE', 'en');
    $desktopResult = null;
    $mobileResult = null;
    if ($strategy == 'desktop') {
      //Desktop
      $desktop = $caller->getResponse($url, InsightsCaller::STRATEGY_DESKTOP);
      $desktopResult = $desktop->getMappedResult();
    } else {
      //Mobile
      $mobile = $caller->getResponse($url, InsightsCaller::STRATEGY_MOBILE);
      $mobileResult = $mobile->getMappedResult();
    }

    $this->rawResult = array(
      'desktop' => $desktopResult,
      'mobile'  => $mobileResult
    );
  }

  //Parse raw result to readable format and remove unused result
  protected function parseResult($strategy)
  {
    $data = $this->rawResult[$strategy];
    // $screenshot = $this->parseScreenshot($data);
    // $suggestions = $this->parseSuggestion($data);

    // $this->finalResult[$strategy] = $data;

    $this->finalResult[$strategy] = array(
      // 'code'         => $data['responseCode'],
      'url'          => 'https://developers.google.com/speed/pagespeed/insights/?url='.$this->url,
      // 'title'        => $data['title'],
      // 'score'        => $data['ruleGroups']['SPEED']['score'],
      'data'          => $data,
      // 'screenshot'   => $screenshot
    );

    // foreach ($suggestions as $key => $value) {
    //   $this->finalResult[$strategy]['suggestions'][] = $suggestions[$key];
    // }
  }

  //Parse website screenshot from raw data
  protected function parseScreenshot($data)
  {
    $tmp1 = str_replace('_', '/', $data['screenshot']['data']);
    $tmp2 = str_replace('-', '+', $tmp1);
    $screenshot = 'data:image/jpeg;base64,'.$tmp2;
    return $screenshot;
  }

  //Parse test suggestions from raw data
  protected function parseSuggestion($data)
  {
    $suggestion = array();
    foreach($data['formattedResults']['ruleResults'] as $key => $value)
    {
      if($value['ruleImpact'] >= 3.0)
      {
        $suggestion[] = $value['localizedRuleName'];
      }
    }

    if($suggestion == NULL)
    {
      $suggestion[0] = 'No high impact suggestions. Good job!';
    }

    return $suggestion;
  }

  //Get result for desktop
  public function desktop()
  {
    $this->parseResult('desktop');
    //store the original data 
    if($this->finalResult['desktop'] != null)
    {
      $content = json_encode($this->finalResult['desktop'], JSON_PRETTY_PRINT);
      $path = 'result/'.$this->folder_name.'/'.$this->time;
      //File::makeDirectory($path, 0775, true, true);
      Storage::disk('local')->makeDirectory($path, 2775, true);
      Storage::disk('local')->put($path.'/pagespeed-desktop.json', $content);
      return $path.'/pagespeed-desktop.json';
    }
  }

  //Get result for mobile
  public function mobile()
  {
    $this->parseResult('mobile');
    if($this->finalResult['mobile'] != null)
    {
        $content = json_encode($this->finalResult['mobile'], JSON_PRETTY_PRINT);
        $path = 'result/'.$this->folder_name.'/'.$this->time;
        //File::makeDirectory($path, 0775, true, true);
        Storage::disk('local')->makeDirectory($path, 2775, true);
        Storage::disk('local')->put($path.'/pagespeed-mobile.json', $content);
        return $path.'/pagespeed-mobile.json';
    }
  }
}

?>
