<?php

namespace App\Validator;

use App\Entity\Figure;
use App\Repository\FigureRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SlugUniqueValidator extends ConstraintValidator
{
    public function __construct(private SluggerInterface $slugger, private FigureRepository $figureRepository)
    {
    }
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\SlugUnique */

        if (null === $value || '' === $value) {
            return;
        }




        /* @var Figure $value
             */
        if ($this->figureRepository->countSlug($this->slugger->slug($value->getName()), $value) > 0) {

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value->getName())
                ->addViolation();
        }
    }
}
