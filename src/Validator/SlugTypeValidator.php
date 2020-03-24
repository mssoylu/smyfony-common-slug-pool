<?php

namespace App\Validator;

use App\Repository\SlugRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SlugTypeValidator extends ConstraintValidator
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
    public function validate($entity, Constraint $constraint)
    {
        $existingSlug = $this->slugRepository->findOneBy([
            'slug' => $entity->getSlug()
        ]);

        if (!$existingSlug) {
            return;
        }

        if ($entity->getId() == $existingSlug->getItemId() && $this->getClassName($entity) == $existingSlug->getType()) {
            return;
        }

        /* @var $constraint \App\Validator\SlugType */
        $this->context->buildViolation($constraint->message)
            ->addViolation();
    }

    /**
     * @param $entity
     * @return string
     */
    private function getClassName($entity)
    {
        $arr = explode('\\', get_class($entity));
        return end($arr);
    }
}
