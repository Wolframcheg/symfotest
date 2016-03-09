<?php
/**
 * Created by PhpStorm.
 * User: device
 * Date: 04.03.16
 * Time: 15:50
 */

namespace AppBundle\Services;


use AppBundle\Traits\GenerateOutput;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PassControl
{
    use GenerateOutput;

    private $doctrine;

    private $checkAnswers;

    public function __construct(RegistryInterface $registryInterface, $checkAnswers)
    {
        $this->doctrine = $registryInterface;
        $this->checkAnswers = $checkAnswers;
    }

    public function process(array $data)
    {
        $rating = $this->checkAnswers->checkAnswers($data);
        $passModule = $this->doctrine->getRepository('AppBundle:PassModule')
            ->findOneById($data['idPassModule']);

        $passModule->addAnsweredQuestionId($passModule->getCurrentQuestion()->getId());

        $nextQuestionForPass = $this->doctrine->getRepository('AppBundle:Question')
            ->getNextQuestionForPass($passModule);

        $passModule->addRating($rating);


        if($nextQuestionForPass === null) {
            $passModule->setIsActive(false);
            $passModule->setTimeFinish(new \DateTime());
            $this->doctrine->getEntityManager()->flush();
            /* todo serviceStates */
            return $this->generateOutput('redirect_to_result', 301, $data['idPassModule']);
        }

        $passModule->setCurrentQuestion($nextQuestionForPass);
        $this->doctrine->getEntityManager()->flush();

        return $this->generateOutput('redirect_to_pass', 301, $data['idPassModule']);
    }
}
