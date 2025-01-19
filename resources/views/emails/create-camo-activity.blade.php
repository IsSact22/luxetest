<x-mail::message>
    # LUXE PLUS

    Estimado/a usuario/a, {{ $user['name'] }} / {{ $user['email'] }}
    **[Click aquí para español](#spanish-message)**

    Nos complace informarle que una nueva actividad se a creada con éxito, y lleva por nombre:
    **{{ $camoActivity['name'] }}** .

    Atentamente,
    El equipo de **LUXE PLUS**

    ---

    # LUXE PLUS {#english-message}

    Dear user, {{ $user['name'] }} / {{ $user['email'] }}

    We are pleased to inform you that the activity **{{ $camoActivity['name'] }}** has been successfully created.

    Sincerely,
    The **LUXE PLUS** Team
</x-mail::message>
