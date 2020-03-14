<?php

namespace App\Validator;

use App\Repository\SlugRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SlugValidator extends ConstraintValidator
{
    private $slugRepository;

    /**
     * SlugValidator constructor.
     * @param SlugRepository $slugRepository
     */
    public function __construct(SlugRepository $slugRepository)
    {
        $this->slugRepository = $slugRepository;
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        $existingSlug = $this->slugRepository->findOneBy([
            'slug' => $value
        ]);

        if (!$existingSlug) {
            return;
        }

        /* @var $constraint \App\Validator\Slug */
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }
}
