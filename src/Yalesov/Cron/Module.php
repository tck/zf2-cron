<?php
namespace Yalesov\Cron;

use Yalesov\Yaml\Yaml;
use Laminas\EventManager\Event;
use Laminas\ModuleManager\Feature\AutoloaderProviderInterface;

/**
 * Cron module
 *
 * @author yalesov <yalesov@cogito-lab.com>
 * @license GPL http://opensource.org/licenses/gpl-license.php
 */
class Module implements AutoloaderProviderInterface
{
  public function getAutoloaderConfig()
  {
    return array(
      'Laminas\Loader\StandardAutoloader' => array(
        'namespaces' => array(
          __NAMESPACE__ => __DIR__,
        ),
      ),
    );
  }

  public function getConfig()
  {
    return Yaml::parse(__DIR__ . '/../../../config/module.config.yml');
  }

  public function onBootstrap(Event $e)
  {
    $app = $e->getApplication();
    $app->getEventManager()
      ->attach('dispatch', array($this, 'setLayout'), -100);
  }

  public function setLayout(Event $e)
  {
    $matches = $e->getRouteMatch();
    $controller = $matches->getParam('controller');
    if (0 !== stripos($controller, __NAMESPACE__, 0)) {
      return;
    }

    $viewModel = $e->getViewModel();
    $viewModel->setTemplate('cron/layout');
  }
}
