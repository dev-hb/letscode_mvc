@content(layout.guest)

<h2>This is product page</h2>

<h3>The params is id = {{ get('ref') }}  {{ get('name') }}</h3>

{ @for $names as $name }
    { @if contains($name, l) }
        <p>
            $name
        </p>
    { @endif }
{ @end }

<script>
    setTimeout(() => {
        document.getElementById('page_product').setAttribute("class", "nav-item active")
    }, 0);
</script>