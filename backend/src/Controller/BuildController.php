<?php
    namespace App\Controller;


    use App\Base\AbstractController;
    use App\Model\Builds;

    class BuildController extends AbstractController
    {
        public function process(string $method, int $build = 0)
        {
            switch ($method) {
                case 'GET': {
                    if ($build > 0) {
                        $response = $this->getBuild($build);
                    } else {
                        $response = $this->getAllBuilds();
                    }
                    break;
                }
                default: {
                    $this->notFound();
                }
            }
            header($response['status_code_header']);
            if ($response['body']) {
                echo $response['body'];
            }
        }

        private function getAllBuilds()
        {
            $build = new Builds($this->db);
            $result = $build->findAll();

            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);

            return $response;
        }

        private function getBuild(int $build)
        {
            $buildModel = new Builds($this->db);
            $result = $buildModel->find($build);
            
            $response['status_code_header'] = 'HTTP/1.1 200 OK';
            $response['body'] = json_encode($result);

            return $response;
        }

        private function notFound()
        {
            $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
            $response['body'] = null;

            return $response;
        }
    }