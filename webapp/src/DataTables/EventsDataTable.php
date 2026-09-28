<?php

namespace App\DataTables;

use App\Entity\Event;
use Pentiminax\UX\DataTables\Attribute\AsDataTable;
use Pentiminax\UX\DataTables\Column\TemplateColumn;
use Pentiminax\UX\DataTables\Column\TextColumn;
use Pentiminax\UX\DataTables\Column\UrlColumn;
use Pentiminax\UX\DataTables\Model\AbstractDataTable;
use Pentiminax\UX\DataTables\Model\Action;
use Pentiminax\UX\DataTables\Model\Actions;
use Pentiminax\UX\DataTables\Model\DataTable;
use Symfony\Component\Routing\RouterInterface;

#[AsDataTable(Event::class)]
final class EventsDataTable extends AbstractDataTable
{
    public function __construct(
        private RouterInterface $router,
    ) {}

    public function configureColumns(): iterable
    {
        yield TemplateColumn::new('action', 'label.action')
            ->setTemplate('events/_action_badge.html.twig')
        ;

        yield TextColumn::new('licensePlate', 'label.license_plate');

        yield UrlColumn::new('recognizedUser', 'label.recognized_user')
            ->linkToUrl(fn (Event $event) => $event->getRecognizedUser() ? $this->router->generate('app_users_show', ['id' => $event->getRecognizedUser()->getId()]) : null)
        ;
    }

    public function configureDataTable(DataTable $table): DataTable
    {
        return $table
            ->serverSide()
            ->processing()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setColumnLabel('label.empty_label')
            ->add(
                Action::detail('action.show')
                    ->setClassName('btn btn-sm btn-outline-primary')
                    ->icon('bi bi-eye')
                    ->linkToRoute('app_events_show', fn (Event $event) => ['id' => $event->getId()])
            )
        ;
    }
}
