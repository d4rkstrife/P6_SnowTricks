<?php

namespace App\Validator;

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

        /*   $count = $this->figureRepository->count(['slug' => $this->slugger->slug($value)]);

        if ($count !== 0) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }*/
        $figure = $this->figureRepository->findBy(['slug' => $this->slugger->slug($value)]);
        if (count($figure) > 0 && $figure[0]->getName() !== $value) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}
