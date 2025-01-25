<x-mail::message>
    # LUXE PLUS

    Estimado/a usuario/a, {{ $mailData['name'] }} / {{ $mailData['email'] }}
    **[Click here for English](#english-message)**

    Nos complace darte la bienvenida a **LUXE PLUS**, la aplicación diseñada para el control de **Gestión de la
    Aeronavegabilidad Continua**.

    Estamos aquí para ayudarte a maximizar la eficiencia y la seguridad en la gestión de la aeronavegabilidad.
    En LUXE PLUS, encontrarás las herramientas necesarias para llevar a cabo un seguimiento efectivo y cumplir con las
    normativas vigentes, asegurando así que tus operaciones aéreas sean siempre seguras y confiables.

    Si en algún momento tienes preguntas o necesitas asistencia, no dudes en ponerte en contacto con nosotros.

    ¡Gracias por formar parte de LUXE PLUS!

    Atentamente,
    El equipo de **LUXE PLUS**

    ---

    # LUXE PLUS {#english-message}

    Dear user, {{ $mailData['name'] }} / {{ $mailData['email'] }}

    We are pleased to welcome you to **LUXE PLUS**, the application designed for the control of **Continuous
    Airworthiness Management**.

    We are here to help you maximize efficiency and safety in the management of airworthiness. In LUXE PLUS, you will
    find the necessary tools to effectively track and comply with current regulations, ensuring that your flight
    operations are always safe and reliable.

    If you have any questions or need assistance at any time, please do not hesitate to reach out to us.

    Thank you for being part of LUXE PLUS!

    Sincerely,
    The **{{ config('app.name') }}** Team
</x-mail::message>
