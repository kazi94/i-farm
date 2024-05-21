<div>
    @foreach ($getRecord()->intrantsPrincipesActifs as $intrantPrincipeActif)
        <div class="flex items-center">
            <span
                class="inline-flex items-center rounded-md bg-primary-600 px-2 py-1 text-xs font-medium  ring-1 ring-inset ">
                {{ $intrantPrincipeActif->principeActif->name_fr }}
            </span>
        </div>
    @endforeach
</div>
