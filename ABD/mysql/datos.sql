use abd;

TRUNCATE TABLE `alquiler`;
TRUNCATE TABLE `tarjeta`;
TRUNCATE TABLE `valoracion`;
TRUNCATE TABLE `pelicula`;
TRUNCATE TABLE `usuario`;


INSERT INTO `usuario` (`id`, `correo`, `nombreUsuario`, `contrasena`, `rol`) VALUES
(1, 'admin@gmail.com', 'admin', '$2y$10$meHVFJK6MnRlXGg7Sa5LzuzUWDyOKCWeB13fMXYK3vbeP8ERlE8TG', 1),
(2, 'usuario@gmail.com', 'usuario', '$2y$10$c3AsaIfscpS1yStdSFj3YejSgKk6GmCE9TScx6x4F0lTDlwf5OZjW', 0);

INSERT INTO `pelicula` (`id`, `nombre`, `descripcion`, `precio`) VALUES
(1, 'Pelicula 1', 'Esta es mi primer pelicula', 3),
(2, 'Pelicula 2', 'Esta es mi segunda pelicula', 10);

INSERT INTO `tarjeta` (`id`, `idUsuario`, `numeroTarjeta`, `fechaTarjeta`, `cvvTarjeta`) VALUES
(1, 1, '1234567890123456', '2024-03-03', 123),
(2, 2, '9999999999999999', '2024-01-03', 133);

INSERT INTO `alquiler` (`id`, `idUsuario`, `idPelicula`, `fechaInicio`, `fechaFin`) VALUES
(1, 1, 1, '2024-05-10', '2024-06-10'),
(2, 1, 2, '2024-05-11', '2024-06-11'),
(3, 2, 1, '2024-05-12', '2024-06-12'),
(4, 2, 2, '2024-05-12', '2024-06-12');

INSERT INTO `valoracion` (`id`, `idUsuario`, `idPelicula`, `puntuacion`, `comentario`) VALUES
(1, 1, 1, 10, 'La mejor para ser su primera pelicula'),
(2, 1, 2, null, null),
(3, 2, 1, null, 'Aun no sé que puntuacion poner'),
(4, 2, 2, 1, 'Muy mala la segunda pelicula');
