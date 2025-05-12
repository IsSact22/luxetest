<x-mail::message>
    # LUXE PLUS

    Estimado/a usuario/a, {{ $user['name'] }} / {{ $user['email'] }}
    **[Click aquí para español](#spanish-message)**

    Nos complace informarle que la actividad **{{ $camoActivity['name'] }}** ha sido actualizada.

    Atentamente,
    El equipo de **LUXE PLUS**

    ---

    # LUXE PLUS {#english-message}

    Dear user, {{ $user['name'] }} / {{ $user['email'] }}

    We are pleased to inform you that the activity **{{ $camoActivity['name'] }}** has been updated.

    Sincerely,
    The **LUXE PLUS** Team
</x-mail::message>
