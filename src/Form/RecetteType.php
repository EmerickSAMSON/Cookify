<?php

namespace App\Form;

use App\Entity\Recette;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\AsciiSlugger;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class ,[
                
            ])
            ->add('content')
            ->add('duration', IntegerType::class, [
                "required" => false
            ])
            ->add('slug', TextType::class, [
                "required" => false
            ])
            ->addEventListener(FormEvents::PRE_SUBMIT, $this->autoSlug(...))
            ->addEventListener(FormEvents::POST_SUBMIT, $this->autoDatetime(...))
        ;
    }

    public function autoSlug(PreSubmitEvent $event): void
    {
        $data = $event->getData();
        if (empty($data["slug"])) {
            $slugger = new AsciiSlugger();
            $data["slug"] = strtolower($slugger->slug($data["title"]));
            $event->setData($data);
        }
    }

    public function autoDatetime(PostSubmitEvent $event) : void 
    {
        $data = $event->getData();
        if (!($data instanceof Recette)) {
            return;
        };
        
        $data->setUpdatedAt(new DateTimeImmutable());
        if (!$data->getId()) {
            $data->setCreatedAt(new DateTimeImmutable());
        };
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
