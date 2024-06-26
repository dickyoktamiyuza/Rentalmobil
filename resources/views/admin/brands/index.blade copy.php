<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Brands') }}
        </h2>
    </x-slot>

    <x-slot name="script">
        <script>
            var datatable;

            function loadDataTable(trashed) {
                if (datatable) {
                    datatable.destroy(); // Hancurkan DataTable sebelum membuat yang baru
                }
                datatable = $('#dataTable').DataTable({
                    processing: true,
                    serverSide: true,
                    stateSave: true,
                    ajax: {
                        url: '{!! url()->current() !!}',
                        data: function(d) {
                            d.trashed = trashed;
                        },
                    },
                    language: {
                        url: '//cdn.datatables.net/plug-ins/1.12.1/i18n/id.json'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,

                            className: 'text-center p-0'
                        },
                        {
                            data: 'name',
                            name: 'name',
                            className: 'text-center p-0'
                        },
                        {
                            data: 'slug',
                            name: 'slug',
                            className: 'text-center p-0'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            // width: '15%'
                        },
                    ],
                });
            }
            $(document).ready(function() {
                loadDataTable(0);
                $('#trashedDropdown').on('change', function() {
                    var trashed = $(this).val();
                    loadDataTable(trashed);
                });
            });
        </script>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="mb-10">
                <a href="{{ route('admin.brands.create') }}"
                    class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                    + Buat Brand
                </a>
            </div>
            <div class="overflow-hidden shadow sm:rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="mb-4">
                        <select id="trashedDropdown"
                            class="block w-32 mt-1 p-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 transition ease-in-out duration-150">
                            <option value="0">Aktif</option>
                            <option value="1">Dihapus</option>
                        </select>
                    </div>

                    <table id="dataTable" class="display responsive nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th style="max-width: 1%">No</th>
                                <th>Nama</th>
                                <th>Slug</th>
                                <th style="max-width: 1%">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>

</style>
