CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `comanda` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `configuration` (
  `id` int(11) NOT NULL,
  `titlesite` varchar(250) NOT NULL,
  `descsite` varchar(250) NOT NULL,
  `logo` varchar(250) NOT NULL,
  `direccion` varchar(250) NOT NULL,
  `telefono` varchar(250) NOT NULL,
  `cateco` int(11) NOT NULL,
  `mesas` int(11) NOT NULL,
  `estilo` int(11) NOT NULL,
  `color` int(11) NOT NULL,
  `porcentaje` int(11) NOT NULL,
  `divisa` varchar(20) NOT NULL,
  `smtp` varchar(250) NOT NULL,
  `usersmtp` varchar(250) NOT NULL,
  `passmtp` varchar(250) NOT NULL,
  `smtport` int(11) NOT NULL,
  `smtpactive` int(11) NOT NULL,
  `decimals` int(11) NOT NULL,
  `miles` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `configuration` (`id`, `titlesite`, `descsite`, `logo`, `direccion`, `telefono`, `cateco`, `mesas`, `estilo`, `color`, `porcentaje`, `divisa`, `smtp`, `usersmtp`, `passmtp`, `smtport`, `smtpactive`, `decimals`, `miles`) VALUES
(1, 'Granero en llamas ', 'Sistema de Cafeterias', 'default.png', 'Entre calle 9 y calle 10 ', '8441234569', 1, 6, 1, 1, 15, 'MXN', 'smtp.doamin.com', 'noreply@domain.com', 'passdefault', 465, 1, 1, 2);

CREATE TABLE `cuentas` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `insumos` longtext,
  `idmesa` bigint(20) NOT NULL,
  `fechahora` datetime NOT NULL,
  `nota` text,
  `hash` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `insumos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` varchar(250) NOT NULL,
  `categoria` int(11) NOT NULL,
  `codigo` int(11) NOT NULL,
  `precio` decimal(15,2) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `insumos` (`id`, `nombre`, `descripcion`, `categoria`, `codigo`, `precio`, `status`) VALUES
(1, 'Vaquera', 'Res, Queso Americano, Manchego, Tocino y Guacamole', 1, 123, '89.00', 1),
(2, 'Hawaiiana', 'Res, Queso Oaxaca, Americano, Piña, y Salchicha', 1, 456, '79.00', 1),
(3, 'Granera ', 'Pechuga de pollo asada, Queso americano con base de vegetales. ', 1, 789, '49.00', 1),
(4, 'Super Crunch', 'Pollo crunchy, Aros de Cebolla caseseros y Manchego Crunchy.', 1, 98, '79.00', 1),
(5, 'Sheriff', 'Arrachera marinada y fileteada, Queso manchego y Guacamole.', 1, 76, '110.00', 1),
(6, 'Forajido', 'Pechuga de pollo estilo KFC, Manchego y Tocino ', 1, 54, '75.00', 1),
(7, 'Agua natural', 'Rica agua de sabor', 2, 32, '20.00', 1),
(8, 'Arizona', 'Arizona', 2, 1, '25.00', 1),
(9, 'Boing', 'Boing de sabor', 2, 2, '25.00', 1),
(10, 'Coca', 'Coca fria', 2, 3, '25.00', 1),
(11, 'Dedos de queso', 'Dedos de queso', 3, 4, '70.00', 1),
(12, 'Nuggets de pollo', 'Nuggets', 3, 5, '40.00', 1),
(13, 'Sandwich 3 quesos', 'Sandwich', 3, 6, '25.00', 1),
(14, 'Tenders de pechuga de pollo', 'Tenders', 3, 7, '65.00', 1),
(15, 'Super papas, aros y nachos', 'Paquete surtido', 3, 8, '75.00', 1),
(16, 'Orden de papas o aros de cebolla', 'Ricas papas y aros de cebolla', 3, 10, '60.00', 1),
(17, 'Hot Dog espacial', 'Rico Hot Dog', 3, 11, '55.00', 1),
(18, 'Super nachos', 'pequete surtido de nachos', 3, 22, '75.00', 1),
(19, 'Super papas ', 'Papas combo', 3, 33, '75.00', 1);


CREATE TABLE `mesas` (
  `id` bigint(11) NOT NULL,
  `numesa` int(11) NOT NULL,
  `fechahora` datetime NOT NULL,
  `cuentas` varchar(50) DEFAULT NULL,
  `encargado` int(11) NOT NULL,
  `hash` varchar(250) NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `stats` (
  `id` int(11) NOT NULL,
  `insumo` int(11) NOT NULL,
  `mes` varchar(2) NOT NULL,
  `cantidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `userpass` varchar(250) NOT NULL,
  `usermail` varchar(250) NOT NULL,
  `status` int(11) NOT NULL,
  `usertype` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `userpass`, `usermail`, `status`, `usertype`) VALUES
(20, 'nacho', 'nacho', '$2y$10$kf6tG0O6jbifndHTx8cwxeNbcfwB19t7fE7t43bZM3UhEokDIG5RS', 'nacho@gmail.com', 2, 2),
(21, 'nuki', 'nuki', '$2y$10$sUTA7pZ2o/NQ7VM3h/7FS.lZ3LCDN.7rIwRlX2Mkggk7/GEmQkIWC', 'muki@gmail.com', 2, 1);

CREATE TABLE `ventas` (
  `id` bigint(11) NOT NULL,
  `idmesa` int(11) NOT NULL,
  `mesa` int(11) NOT NULL,
  `cuenta` varchar(100) NOT NULL,
  `usuario` int(11) NOT NULL,
  `articulos` text NOT NULL,
  `ano` varchar(4) NOT NULL,
  `mes` varchar(2) NOT NULL,
  `dia` varchar(2) NOT NULL,
  `hora` varchar(10) NOT NULL,
  `total` decimal(15,2) NOT NULL,
  `pagocon` decimal(15,2) NOT NULL,
  `tipopago` int(11) NOT NULL,
  `cambio` decimal(15,2) NOT NULL,
  `hash` varchar(250) NOT NULL,
  `propina` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `configuration`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `cuentas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `insumos`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `stats`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `cuentas`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `insumos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

ALTER TABLE `mesas`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

ALTER TABLE `ventas`
  MODIFY `id` bigint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;