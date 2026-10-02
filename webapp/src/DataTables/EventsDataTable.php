<?php

namespace App\DataTables;

use App\Entity\Event;
use Doctrine\ORM\QueryBuilder;
use Pentiminax\UX\DataTables\Attribute\AsDataTable;
use Pentiminax\UX\DataTables\Column\DateColumn;
use Pentiminax\UX\DataTables\Column\TemplateColumn;
use Pentiminax\UX\DataTables\Column\TextColumn;
use Pentiminax\UX\DataTables\Column\UrlColumn;
use Pentiminax\UX\DataTables\DataTableRequest\DataTableRequest;
use Pentiminax\UX\DataTables\Filter\ChoiceFilter;
use Pentiminax\UX\DataTables\Filter\DateRangeFilter;
use Pentiminax\UX\DataTables\Filter\TextFilter;
use Pentiminax\UX\DataTables\Model\AbstractDataTable;
use Pentiminax\UX\DataTables\Model\Action;
use Pentiminax\UX\DataTables\Model\Actions;
use Pentiminax\UX\DataTables\Model\DataTable;
use Pentiminax\UX\DataTables\Model\Filters;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[AsDataTable(Event::class)]
final class EventsDataTable extends AbstractDataTable
{
    public function __construct(
        private RouterInterface $router,
        private TranslatorInterface $translator,
    ) {}

    public function configureColumns(): iterable
    {
        yield DateColumn::new('createdAt', 'label.created_at')
            ->setFormat('Y-m-d H:i:s')
        ;

        yield TemplateColumn::new('action', 'label.action')
            ->setTemplate('events/_action_badge.html.twig')
        ;

        yield TextColumn::new('licensePlate', 'label.license_plate');

        yield UrlColumn::new('recognizedUser', 'label.recognized_user')
            ->setField('recognizedUser.fullName')
            ->linkToUrl(fn (Event $event) => $event->getRecognizedUser() ? $this->router->generate('app_users_show', ['id' => $event->getRecognizedUser()->getId()]) : null)
        ;
    }

    public function configureDataTable(DataTable $table): DataTable
    {
        return $table
            ->serverSide()
            ->processing()
            ->autoWidth(false)
            ->order([
                [0, 'desc'],
            ])
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->setColumnLabel('label.empty_label')
            ->add(
                Action::detail()
                    ->label($this->translator->trans('action.show'))
                    ->setClassName('btn btn-sm btn-outline-primary')
                    ->icon('bi bi-eye')
                    ->linkToRoute('app_events_show', fn (Event $event) => ['id' => $event->getId()])
            )
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(
                DateRangeFilter::new('createdAt')
                    ->label('label.created_at')
            )
            ->add(
                ChoiceFilter::new('action')
                    ->label('label.action')
                    ->options([
                        $this->translator->trans('label.action_entry') => Event::ACTION_ENTRY,
                        $this->translator->trans('label.action_exit') => Event::ACTION_EXIT,
                    ])
            )
            ->add(
                TextFilter::new('licensePlate')
                    ->label('label.license_plate')
            )
            ->add(
                TextFilter::new('recognizedUser.fullName')
                    ->label('label.recognized_user')
            )
        ;
    }

    protected function customizeQueryBuilder(QueryBuilder $qb, DataTableRequest $request): QueryBuilder
    {
        $qb
            ->leftJoin('e.recognizedUser', 'recognizedUser')
            ->addSelect('recognizedUser')
        ;

        return $qb;
    }
}
