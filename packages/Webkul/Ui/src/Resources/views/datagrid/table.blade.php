<div class="table">
    <table class="{{ $css->table }}">
        @include('ui::datagrid.table.head')
        @include('ui::datagrid.table.body')
    </table>
    @include('ui::datagrid.pagination')
</div>