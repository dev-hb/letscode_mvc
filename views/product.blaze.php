@content(layout.guest)

<h2>This is product page</h2>

<h3>The params is id = {{ get('ref') }}  {{ get('name') }}</h3>


{ @if 5 <= 15 && 3 > 0 }
<p>
    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aspernatur doloremque eos velit.
    Adipisci dolores est mollitia officia recusandae. Iusto, nulla!
</p>
{ @endif }