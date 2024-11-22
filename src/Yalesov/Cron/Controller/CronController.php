<?php
namespace Yalesov\Cron\Controller;

use Yalesov\BackgroundExec\BackgroundExec;
use Laminas\Console\Request as ConsoleRequest;
use Laminas\Console\Response as ConsoleResponse;
use Laminas\Mvc\Controller\AbstractActionController;

/**
 * Cron controller
 *
 * @author yalesov <yalesov@cogito-lab.com>
 * @license GPL http://opensource.org/licenses/gpl-license.php
 */
class CronController extends AbstractActionController
{
  /**
   * run the cron service
   *
   * if called from browser,
   * will suppress output and continue execution in background
   *
   * @return Response|void
   */
  public function indexAction()
  {
    if (!$this->getRequest() instanceof ConsoleRequest) {
      BackgroundExec::start();
    }
    $sm   = $this->getServiceLocator();
    $cron   = $sm->get('cron');
    $em   = $sm->get('doctrine.entitymanager.orm_default');
    $cron
      ->setEm($em)
      ->run();

    $response = $this->getResponse();
    if (!$response instanceof ConsoleResponse) {
      $response->setStatusCode(200);

      return $response;
    }
  }
}
