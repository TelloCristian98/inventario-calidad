-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2023 at 09:06 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

create database if not exists sistema_inventario;

use sistema_inventario;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


--
-- Database: `sistema_inventario`
--

DELIMITER $$
--
-- Procedures
--
CREATE PROCEDURE `actualizar_precio_material` (IN `n_cantidad` DECIMAL, IN `n_precio` DECIMAL(10,2), IN `codigo_material` INT)   BEGIN
    	DECLARE nueva_existencia decimal(10,2);
        DECLARE nuevo_total decimal(10,2);
        DECLARE nuevo_precio decimal(10,2);
        DECLARE cant_actual int;
        DECLARE pre_actual decimal(10,2);
        DECLARE actual_existencia decimal(10,2);
        DECLARE actual_precio decimal(10,2);
        SELECT `CostoPorUnidad_Material`, `Existencia_Material` INTO actual_precio, actual_existencia FROM material WHERE Id_Material = codigo_material;
        SET nueva_existencia = actual_existencia + n_cantidad;
        SET nuevo_total = (actual_existencia * actual_precio) + (n_cantidad * n_precio);
        SET nuevo_precio = nuevo_total / nueva_existencia;
        UPDATE material SET `Existencia_Material` = nueva_existencia, `CostoPorUnidad_Material` = nuevo_precio WHERE `Id_Material` = codigo_material;
        SELECT nueva_existencia, nuevo_precio, nuevo_total;
    END$$

CREATE PROCEDURE `add_producto_temp` (`codigo_mateiral` INT, `cantidad_material` INT, `token_user` VARCHAR(50))   BEGIN
    	DECLARE precio_actual decimal(10,2);
        SELECT CostoPorUnidad_Material INTO precio_actual FROM material WHERE material.Id_Material = codigo_mateiral;
        INSERT INTO producto_temp(Id_Material_Prod_temp, Id_Usuario_Prod_temp, Existencia_Prod_temp, PrecioUnit_Prod_temp) VALUES (codigo_mateiral, token_user, cantidad_material, precio_actual);
        
        SELECT producto_temp.Id_Producto_temp, producto_temp.Id_Material_Prod_temp, material.Nombre_Material, producto_temp.Existencia_Prod_temp, producto_temp.PrecioUnit_Prod_temp FROM producto_temp INNER JOIN material ON producto_temp.Id_Material_Prod_temp = material.Id_Material WHERE producto_temp.Id_Usuario_Prod_temp = token_user;
    END$$

CREATE PROCEDURE `del_producto_temp` (`id_producto_temp` INT, `token_user` VARCHAR(50))   BEGIN
    	DELETE FROM producto_temp WHERE producto_temp.Id_Producto_temp = id_producto_temp;
        SELECT producto_temp.Id_Producto_temp, producto_temp.Id_Material_Prod_temp, material.Nombre_Material, producto_temp.Existencia_Prod_temp, producto_temp.PrecioUnit_Prod_temp FROM producto_temp INNER JOIN material ON producto_temp.Id_Material_Prod_temp = material.Id_Material WHERE producto_temp.Id_Usuario_Prod_temp = token_user;
    END$$

CREATE PROCEDURE `procesar_producto` (IN `id_usuario` INT, IN `desc_producto` VARCHAR(50), IN `foto_producto` TEXT)   BEGIN
    	DECLARE producto int;
        DECLARE kardex int;
        DECLARE material int;
        DECLARE registros int;
        DECLARE total decimal(10,2);
        DECLARE nueva_existencia int;
        DECLARE existencia_actual int;
        DECLARE tmp_cod_material int;
        DECLARE tmp_cant_material int;
        DECLARE unit_k decimal(10,2);
        DECLARE valorS_k decimal(10,2);
        DECLARE totalSaldo_k decimal(10,2);
        DECLARE id_material int;
        DECLARE a int;
        SET a = 1;
        
        
        
        CREATE TEMPORARY TABLE tbl_tmp_producttoken(
            id_temp bigint NOT null AUTO_INCREMENT PRIMARY KEY,
            cod_material bigint,
            cant_material int
        );
        SET registros = (SELECT COUNT(*) FROM producto_temp WHERE producto_temp.Id_Usuario_Prod_temp = id_usuario);
        
        IF registros > 0 THEN
        	INSERT INTO tbl_tmp_producttoken(cod_material, cant_material) SELECT producto_temp.Id_Material_Prod_temp, producto_temp.Existencia_Prod_temp FROM producto_temp WHERE producto_temp.Id_Usuario_Prod_temp = id_usuario;        
            
            INSERT INTO producto(producto.Id_Usuario_Prod, producto.Desc_Producto, producto.Foto_Prod) VALUES (id_usuario, desc_producto, foto_producto);
            SET producto = LAST_INSERT_ID();
            
            
            INSERT INTO producto_detalle(Id_Producto_Prod, Id_Material_Prod_Detalle, Existencia_Prod_Detalle, PrecioUnit_Prod_Detalle) SELECT (producto) as id_producto, producto_temp.Id_Material_Prod_temp, producto_temp.Existencia_Prod_temp, producto_temp.PrecioUnit_Prod_temp FROM producto_temp WHERE producto_temp.Id_Usuario_Prod_temp = id_usuario;
   
            WHILE a <= registros DO
            	SELECT cod_material, cant_material INTO tmp_cod_material, tmp_cant_material FROM tbl_tmp_producttoken WHERE id_temp = a;
                SELECT material.Existencia_Material INTO existencia_actual FROM material WHERE material.Id_Material = tmp_cod_material;
                SET nueva_existencia = existencia_actual - tmp_cant_material;
                UPDATE material SET material.Existencia_Material = nueva_existencia WHERE material.Id_Material = tmp_cod_material;
                
                
                SELECT Id_Kardex INTO kardex FROM kardex ORDER BY Id_Kardex DESC LIMIT 1;
                SELECT kardex.Valor_Unit_K INTO unit_k FROM kardex WHERE kardex.Id_Kardex = kardex;
                
                SET valorS_k = tmp_cant_material * unit_k;
                SET totalSaldo_k = unit_k * nueva_existencia;
                /*SET id_material = SELECT material.Id_Material FROM material WHERE material.Id_Material = material;*/
                
                INSERT INTO kardex(kardex.Id_Usuario, kardex.Id_Material, kardex.Desc_K, kardex.Cantidad_Sal_K, kardex.Valor_Sal_K, kardex.Cantidad_Saldo_k, kardex.Valor_Saldo_K, kardex.Valor_Unit_K) VALUES(id_usuario, tmp_cod_material, 'Salida de Material', tmp_cant_material, valorS_k, nueva_existencia, totalSaldo_k, unit_k);
                
                SET a = a + 1;
            END WHILE;
            
            SET total = (SELECT SUM(producto_temp.Existencia_Prod_temp * producto_temp.PrecioUnit_Prod_temp) FROM producto_temp WHERE producto_temp.Id_Usuario_Prod_temp = id_usuario);
            
            UPDATE producto SET producto.PrecioUnit_Prod = total, producto.Existencia_Prod = 1 WHERE producto.Id_Producto = producto; 
            DELETE FROM producto_temp WHERE producto_temp.Id_Usuario_Prod_temp = id_usuario;
            TRUNCATE TABLE tbl_tmp_producttoken;
            SELECT * FROM producto WHERE producto.Id_Producto = producto;
        ELSE
        	SELECT 0;
        END IF;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `Id_Cliente` int(11) NOT NULL,
  `CI_Cliente` varchar(100) NOT NULL,
  `Nombre_Cliente` varchar(100) NOT NULL,
  `Apellido_Cliente` varchar(100) NOT NULL,
  `Telefono_Cliente` varchar(100) NOT NULL,
  `Direccion_Cliente` varchar(100) NOT NULL,
  `Estado_Cliente` int(2) NOT NULL DEFAULT 1,
  `Dateadd_Cliente` datetime NOT NULL DEFAULT current_timestamp(),
  `Id_Usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`Id_Cliente`, `CI_Cliente`, `Nombre_Cliente`, `Apellido_Cliente`, `Telefono_Cliente`, `Direccion_Cliente`, `Estado_Cliente`, `Dateadd_Cliente`, `Id_Usuario`) VALUES
(30000, '17111457896', 'Jaime', 'Estrada', '0985542638', '25 de Noviembre y Maldonado', 1, '2023-08-22 22:00:46', 10000),
(30002, '1711153709', 'Juan', 'Perez', '1234567890', '25 de noviembre', 1, '2023-08-23 18:59:38', 10000),
(30003, '1721286543', 'Jaime', 'Aimara', '0978852456', 'El calzado', 1, '2023-08-28 18:38:58', 10000),
(30004, '1787848203', 'Carmen', 'Encalada', '0975542163', 'El calzado', 1, '2023-09-04 14:04:28', 10000);

-- --------------------------------------------------------

--
-- Table structure for table `configuracion`
--

CREATE TABLE `configuracion` (
  `Id_Conf` int(11) NOT NULL,
  `CI_Conf` varchar(20) NOT NULL,
  `Nombre_Conf` varchar(100) NOT NULL,
  `RazonSocial_Conf` varchar(100) NOT NULL,
  `Telefono_Conf` varchar(20) NOT NULL,
  `Email_Conf` varchar(20) NOT NULL,
  `Direccion_Conf` text NOT NULL,
  `IVA_Conf` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `configuracion`
--

INSERT INTO `configuracion` (`Id_Conf`, `CI_Conf`, `Nombre_Conf`, `RazonSocial_Conf`, `Telefono_Conf`, `Email_Conf`, `Direccion_Conf`, `IVA_Conf`) VALUES
(1, '1754268971-001', 'VibraMueble', 'Empresa S.A.', '0985524576', 'grupo6@email.com', '10 de Agosto y Av. America, Quito.', 12);

-- --------------------------------------------------------

--
-- Table structure for table `factura`
--

CREATE TABLE `factura` (
  `Id_Factura` int(11) NOT NULL,
  `Fecha_Factura` datetime NOT NULL DEFAULT current_timestamp(),
  `Id_Usuario_Fac` int(11) NOT NULL,
  `Id_Cliente_Fac` int(11) NOT NULL,
  `Total_Fac` decimal(10,2) NOT NULL,
  `Estado_Fac` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `factura_detalle`
--

CREATE TABLE `factura_detalle` (
  `Id_Factura_Det` int(11) NOT NULL,
  `Id_Factura` int(11) NOT NULL,
  `Id_Producto_Fac_Det` int(11) NOT NULL,
  `Cantidad_Fac_Det` int(11) NOT NULL,
  `PrecioTotal_Fac_Det` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `factura_temp`
--

CREATE TABLE `factura_temp` (
  `Id_Factura_Temp` int(11) NOT NULL,
  `Id_Factura_Temp_Usuario` int(11) NOT NULL,
  `Id_Factura_Temp_Prod` int(11) NOT NULL,
  `Cantidad_Factura_Temp` int(11) NOT NULL,
  `Precio_Venta_Temp` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kardex`
--

CREATE TABLE `kardex` (
  `Id_Kardex` int(11) NOT NULL,
  `Id_Usuario` int(11) NOT NULL,
  `Id_Material` int(11) NOT NULL,
  `Fecha_K` datetime NOT NULL DEFAULT current_timestamp(),
  `Desc_K` varchar(100) NOT NULL,
  `Valor_Unit_K` float NOT NULL,
  `Cantidad_Ent_K` float NOT NULL,
  `Valor_Ent_K` float NOT NULL,
  `Cantidad_Sal_K` float NOT NULL,
  `Valor_Sal_K` float NOT NULL,
  `Cantidad_Saldo_k` float NOT NULL,
  `Valor_Saldo_K` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kardex`
--

INSERT INTO `kardex` (`Id_Kardex`, `Id_Usuario`, `Id_Material`, `Fecha_K`, `Desc_K`, `Valor_Unit_K`, `Cantidad_Ent_K`, `Valor_Ent_K`, `Cantidad_Sal_K`, `Valor_Sal_K`, `Cantidad_Saldo_k`, `Valor_Saldo_K`) VALUES
(60042, 10000, 50012, '2023-08-25 08:00:29', 'Inventario inicial', 17, 4, 68, 0, 0, 4, 68),
(60043, 10000, 50012, '2023-08-25 08:01:36', 'Entrada de material', 17.21, 3, 17.5, 0, 0, 7, 120.5),
(60044, 10000, 50012, '2023-08-25 08:03:12', 'Entrada de material', 16.36, 6.5, 15.5, 0, 0, 14, 228.97),
(60045, 10000, 50013, '2023-08-25 08:04:51', 'Inventario inicial', 0.8, 16, 12.8, 0, 0, 16, 12.8),
(60046, 10000, 50013, '2023-08-26 08:05:42', 'Entrada de material', 1.8, 5, 5, 0, 0, 21, 37.8),
(60047, 10000, 50013, '2023-08-28 18:39:36', 'Entrada de material', 1.84, 5, 2, 0, 0, 26, 47.8),
(60050, 10000, 50012, '2023-08-29 22:13:08', 'Entrada de material', 17.03, 100, 17, 0, 0, 96, 1634.56),
(60051, 10000, 50013, '2023-08-29 22:16:59', 'Entrada de material', 1.99, 100, 2, 0, 0, 108, 214.72),
(60054, 10000, 50012, '2023-08-29 22:58:51', 'Salida de Material', 1.99, 0, 0, 4, 7.96, 47, 93.53),
(60055, 10000, 50013, '2023-08-29 22:58:51', 'Salida de Material', 1.99, 0, 0, 5, 9.95, 78, 155.22),
(60060, 10000, 50012, '2023-08-30 07:53:04', 'Salida de Material', 1.99, 0, 0, 5, 9.95, 37, 73.63),
(60061, 10000, 50013, '2023-08-30 07:53:04', 'Salida de Material', 1.99, 0, 0, 4, 7.96, 72, 143.28),
(60062, 10000, 50012, '2023-08-30 11:15:21', 'Entrada de material', 17.24, 10, 18, 0, 0, 47, 810.11),
(60063, 10000, 50012, '2023-08-30 11:15:53', 'Salida de Material', 17.24, 0, 0, 5, 86.2, 42, 724.08),
(60064, 10000, 50013, '2023-08-30 11:15:53', 'Salida de Material', 17.24, 0, 0, 1, 17.24, 71, 1224.04),
(60065, 10000, 50012, '2023-09-02 12:49:41', 'Salida de Material', 17.24, 0, 0, 1, 17.24, 41, 706.84),
(60066, 10000, 50012, '2023-09-02 12:49:41', 'Salida de Material', 17.24, 0, 0, 1, 17.24, 40, 689.6);

-- --------------------------------------------------------

--
-- Table structure for table `material`
--

CREATE TABLE `material` (
  `Id_Material` int(11) NOT NULL,
  `Id_Unidad` int(11) NOT NULL,
  `Id_Usuario` int(11) NOT NULL,
  `Nombre_Material` varchar(50) NOT NULL,
  `CostoPorUnidad_Material` float NOT NULL,
  `Existencia_Material` float NOT NULL,
  `Estado_Material` int(2) NOT NULL DEFAULT 1,
  `Dateadd_Material` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `material`
--

INSERT INTO `material` (`Id_Material`, `Id_Unidad`, `Id_Usuario`, `Nombre_Material`, `CostoPorUnidad_Material`, `Existencia_Material`, `Estado_Material`, `Dateadd_Material`) VALUES
(50012, 40002, 10000, 'Lamina de tool negro de 0.7 mm', 17.24, 40, 1, '2023-08-25 08:00:29'),
(50013, 40000, 10000, 'Rodamiento', 1.99, 71, 1, '2023-08-25 08:04:51');

--
-- Triggers `material`
--
DELIMITER $$
CREATE TRIGGER `kardex_after_insert_materiales` AFTER INSERT ON `material` FOR EACH ROW BEGIN
    	INSERT INTO kardex (kardex.Id_Usuario, kardex.Id_Material, kardex.Desc_K, kardex.Valor_Unit_K, kardex.Cantidad_Ent_K, 		kardex.Valor_Ent_K, kardex.Cantidad_Saldo_K, kardex.Valor_Saldo_K)
        VALUES (new.Id_Usuario, new.Id_Material, 'Inventario inicial', new.CostoPorUnidad_Material, new.Existencia_Material, (new.CostoPorUnidad_Material * new.Existencia_Material), new.Existencia_Material, (new.CostoPorUnidad_Material * new.Existencia_Material));
    END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `producto`
--

CREATE TABLE `producto` (
  `Id_Producto` int(11) NOT NULL,
  `Id_Usuario_Prod` int(11) NOT NULL,
  `Desc_Producto` varchar(50) NOT NULL,
  `Existencia_Prod` int(11) NOT NULL,
  `PrecioUnit_Prod` decimal(10,2) NOT NULL,
  `Foto_Prod` text NOT NULL,
  `dateadd_Prod` datetime NOT NULL DEFAULT current_timestamp(),
  `Estado_Prod` int(2) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producto`
--

INSERT INTO `producto` (`Id_Producto`, `Id_Usuario_Prod`, `Desc_Producto`, `Existencia_Prod`, `PrecioUnit_Prod`, `Foto_Prod`, `dateadd_Prod`, `Estado_Prod`) VALUES
(90011, 10000, 'Gabetas', 1, 78.07, 'imagen.png', '2023-08-29 22:58:51', 1),
(90013, 10000, 'Producto 1', 1, 93.11, 'foto.png', '2023-08-30 07:53:04', 1),
(90014, 10000, 'Producto 2', 1, 88.19, 'foto.png', '2023-08-30 11:15:53', 1),
(90015, 10000, 'Producto 3', 1, 34.48, 'foto.png', '2023-09-02 12:49:41', 1);

-- --------------------------------------------------------

--
-- Table structure for table `producto_detalle`
--

CREATE TABLE `producto_detalle` (
  `Id_Producto_Detalle` int(11) NOT NULL,
  `Id_Producto_Prod` int(11) NOT NULL,
  `Id_Material_Prod_Detalle` int(11) NOT NULL,
  `Existencia_Prod_Detalle` decimal(10,2) NOT NULL,
  `PrecioUnit_Prod_Detalle` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producto_detalle`
--

INSERT INTO `producto_detalle` (`Id_Producto_Detalle`, `Id_Producto_Prod`, `Id_Material_Prod_Detalle`, `Existencia_Prod_Detalle`, `PrecioUnit_Prod_Detalle`) VALUES
(100021, 90011, 50012, 4.00, 17.03),
(100022, 90011, 50013, 5.00, 1.99),
(100030, 90013, 50012, 5.00, 17.03),
(100031, 90013, 50013, 4.00, 1.99),
(100033, 90014, 50012, 5.00, 17.24),
(100034, 90014, 50013, 1.00, 1.99),
(100035, 90015, 50012, 1.00, 17.24),
(100036, 90015, 50012, 1.00, 17.24);

-- --------------------------------------------------------

--
-- Table structure for table `producto_temp`
--

CREATE TABLE `producto_temp` (
  `Id_Producto_temp` int(11) NOT NULL,
  `Id_Material_Prod_temp` int(11) NOT NULL,
  `Id_Usuario_Prod_temp` int(11) NOT NULL,
  `Existencia_Prod_temp` int(11) NOT NULL,
  `PrecioUnit_Prod_temp` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `producto_temp`
--

INSERT INTO `producto_temp` (`Id_Producto_temp`, `Id_Material_Prod_temp`, `Id_Usuario_Prod_temp`, `Existencia_Prod_temp`, `PrecioUnit_Prod_temp`) VALUES
(80090, 50012, 10000, 1, 17.24),
(80091, 50013, 10000, 5, 1.99);

-- --------------------------------------------------------

--
-- Table structure for table `rolusuario`
--

CREATE TABLE `rolusuario` (
  `Id_Rol` int(2) NOT NULL,
  `Rol` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rolusuario`
--

INSERT INTO `rolusuario` (`Id_Rol`, `Rol`) VALUES
(20000, 'Administrador'),
(20001, 'Jefe de Produccion'),
(20002, 'Bodeguero'),
(20003, 'Contabilidad');

-- --------------------------------------------------------

--
-- Table structure for table `unidad`
--

CREATE TABLE `unidad` (
  `Id_Unidad` int(11) NOT NULL,
  `Nombre_Unidad` varchar(11) NOT NULL,
  `Nombre_Unidades` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unidad`
--

INSERT INTO `unidad` (`Id_Unidad`, `Nombre_Unidad`, `Nombre_Unidades`) VALUES
(40000, 'unidad', 'unidades'),
(40001, 'metro', 'metros'),
(40002, 'plancha', 'planchas');

-- --------------------------------------------------------

--
-- Table structure for table `usuario`
--

CREATE TABLE `usuario` (
  `Id_Usuario` int(11) NOT NULL,
  `Id_Rol_Us` int(11) NOT NULL,
  `Nombre_Usuario` varchar(100) NOT NULL,
  `Apellido_Usuario` varchar(100) NOT NULL,
  `Correo_Usuario` varchar(100) NOT NULL,
  `Usuario` varchar(100) NOT NULL,
  `Clave_Usuario` varchar(100) NOT NULL,
  `Estado_Usuario` int(2) NOT NULL DEFAULT 1,
  `Foto_Usuario` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuario`
--

INSERT INTO `usuario` (`Id_Usuario`, `Id_Rol_Us`, `Nombre_Usuario`, `Apellido_Usuario`, `Correo_Usuario`, `Usuario`, `Clave_Usuario`, `Estado_Usuario`, `Foto_Usuario`) VALUES
(10000, 20000, 'Cristian', 'Tello', 'admin@adminn.com', 'admin', '21232f297a57a5a743894a0e4a801fc3', 1, 'perfil.jpg'),
(10001, 20002, 'fffff', 'fffhfg', 'fffffff@g', 'ffff', 'd41d8cd98f00b204e9800998ecf8427e', 1, 'highway.png'),
(10002, 20001, 'gggg', 'ggg', 'ggg@g', 'gggg', 'd41d8cd98f00b204e9800998ecf8427e', 1, 'tree.png'),
(10003, 20003, 'Juan', 'Perez', 'ejemplo@email.com', 'juan', '29da068eb085cf10a453da1a15b47eae', 1, 'img_tree.png'),
(10014, 20002, 'Carlos', 'Andrango', 'carlos@email.com', 'carlos23', '232ab968f5e71a084e4ac3794be2327e', 1, 'animal.jpeg'),
(10015, 20002, 'Sergio', 'Busquets', 'sergio@gmail.com', 'sergio123', 'd9bae3215f4677ddf2fa9972e0bf1c00', 1, 'Diagrama de partes interesadas.png'),
(10016, 20001, 'Jordi', 'Alba', 'jordi@email.com', 'jordi123', 'c46fd4b2137465e30e375f844faf28d9', 1, 'repos.png'),
(10017, 20001, 'Jaime', 'Aimara', 'jaime@email.com', 'jaime123', '26eab66329fae7cf234013a4d49eef35', 1, 'user-a-min.png'),
(10018, 20003, 'hhhh', 'hhhh', 'hhhh@g', 'hhhh', 'e61e7de603852182385da5e907b4b232', 1, 'car-removebg.png'),
(10019, 20001, 'Pedro', 'Manrique', 'pedro@email.com', 'pedro123', 'd3ce9efea6244baa7bf718f12dd0c331', 1, 'tree-removebg.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`Id_Cliente`),
  ADD KEY `Id_Usuario` (`Id_Usuario`);

--
-- Indexes for table `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`Id_Conf`);

--
-- Indexes for table `factura`
--
ALTER TABLE `factura`
  ADD PRIMARY KEY (`Id_Factura`),
  ADD KEY `Id_Usuario_Fac` (`Id_Usuario_Fac`),
  ADD KEY `Id_Cliente_Fac` (`Id_Cliente_Fac`);

--
-- Indexes for table `factura_detalle`
--
ALTER TABLE `factura_detalle`
  ADD PRIMARY KEY (`Id_Factura_Det`),
  ADD KEY `Id_Factura` (`Id_Factura`),
  ADD KEY `Id_Producto_Fac_Det` (`Id_Producto_Fac_Det`);

--
-- Indexes for table `factura_temp`
--
ALTER TABLE `factura_temp`
  ADD PRIMARY KEY (`Id_Factura_Temp`),
  ADD KEY `Id_Factura_Temp_Usuario` (`Id_Factura_Temp_Usuario`),
  ADD KEY `Id_Factura_Temp_Prod` (`Id_Factura_Temp_Prod`);

--
-- Indexes for table `kardex`
--
ALTER TABLE `kardex`
  ADD PRIMARY KEY (`Id_Kardex`),
  ADD KEY `Id_Usuario` (`Id_Usuario`),
  ADD KEY `Id_Material` (`Id_Material`);

--
-- Indexes for table `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`Id_Material`),
  ADD KEY `Id_Unidad` (`Id_Unidad`),
  ADD KEY `Id_Usuario` (`Id_Usuario`);

--
-- Indexes for table `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`Id_Producto`),
  ADD KEY `Id_Usuario_Prod` (`Id_Usuario_Prod`);

--
-- Indexes for table `producto_detalle`
--
ALTER TABLE `producto_detalle`
  ADD PRIMARY KEY (`Id_Producto_Detalle`),
  ADD KEY `Id_Producto_Prod` (`Id_Producto_Prod`),
  ADD KEY `Id_Material_Prod_Detalle` (`Id_Material_Prod_Detalle`);

--
-- Indexes for table `producto_temp`
--
ALTER TABLE `producto_temp`
  ADD PRIMARY KEY (`Id_Producto_temp`),
  ADD KEY `Id_Material_Prod_temp` (`Id_Material_Prod_temp`),
  ADD KEY `Id_Usuario_Prod_temp` (`Id_Usuario_Prod_temp`);

--
-- Indexes for table `rolusuario`
--
ALTER TABLE `rolusuario`
  ADD PRIMARY KEY (`Id_Rol`);

--
-- Indexes for table `unidad`
--
ALTER TABLE `unidad`
  ADD PRIMARY KEY (`Id_Unidad`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`Id_Usuario`),
  ADD KEY `Id_Rol_Us` (`Id_Rol_Us`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `Id_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30005;

--
-- AUTO_INCREMENT for table `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `Id_Conf` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `factura`
--
ALTER TABLE `factura`
  MODIFY `Id_Factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110000;

--
-- AUTO_INCREMENT for table `factura_detalle`
--
ALTER TABLE `factura_detalle`
  MODIFY `Id_Factura_Det` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120000;

--
-- AUTO_INCREMENT for table `factura_temp`
--
ALTER TABLE `factura_temp`
  MODIFY `Id_Factura_Temp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130000;

--
-- AUTO_INCREMENT for table `kardex`
--
ALTER TABLE `kardex`
  MODIFY `Id_Kardex` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60067;

--
-- AUTO_INCREMENT for table `material`
--
ALTER TABLE `material`
  MODIFY `Id_Material` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50014;

--
-- AUTO_INCREMENT for table `producto`
--
ALTER TABLE `producto`
  MODIFY `Id_Producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90016;

--
-- AUTO_INCREMENT for table `producto_detalle`
--
ALTER TABLE `producto_detalle`
  MODIFY `Id_Producto_Detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100038;

--
-- AUTO_INCREMENT for table `producto_temp`
--
ALTER TABLE `producto_temp`
  MODIFY `Id_Producto_temp` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80092;

--
-- AUTO_INCREMENT for table `rolusuario`
--
ALTER TABLE `rolusuario`
  MODIFY `Id_Rol` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20004;

--
-- AUTO_INCREMENT for table `unidad`
--
ALTER TABLE `unidad`
  MODIFY `Id_Unidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40003;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `Id_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10020;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `cliente_ibfk_1` FOREIGN KEY (`Id_Usuario`) REFERENCES `usuario` (`Id_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `factura`
--
ALTER TABLE `factura`
  ADD CONSTRAINT `Factura-Cliente` FOREIGN KEY (`Id_Cliente_Fac`) REFERENCES `cliente` (`Id_Cliente`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Factura-Usuario` FOREIGN KEY (`Id_Usuario_Fac`) REFERENCES `usuario` (`Id_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `factura_detalle`
--
ALTER TABLE `factura_detalle`
  ADD CONSTRAINT `FacturaDet-Factura` FOREIGN KEY (`Id_Factura`) REFERENCES `factura` (`Id_Factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FacturaDet-Producto` FOREIGN KEY (`Id_Producto_Fac_Det`) REFERENCES `producto` (`Id_Producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `factura_temp`
--
ALTER TABLE `factura_temp`
  ADD CONSTRAINT `FacturaTemp-Producto` FOREIGN KEY (`Id_Factura_Temp_Prod`) REFERENCES `producto` (`Id_Producto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FacturaTemp-Usuario` FOREIGN KEY (`Id_Factura_Temp_Usuario`) REFERENCES `usuario` (`Id_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `kardex`
--
ALTER TABLE `kardex`
  ADD CONSTRAINT `Kardex-Material` FOREIGN KEY (`Id_Material`) REFERENCES `material` (`Id_Material`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Kardex-Usuario` FOREIGN KEY (`Id_Usuario`) REFERENCES `usuario` (`Id_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `material`
--
ALTER TABLE `material`
  ADD CONSTRAINT `Material-Unidad` FOREIGN KEY (`Id_Unidad`) REFERENCES `unidad` (`Id_Unidad`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Material-Usuario` FOREIGN KEY (`Id_Usuario`) REFERENCES `usuario` (`Id_Usuario`);

--
-- Constraints for table `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `Producto-Usuario` FOREIGN KEY (`Id_Usuario_Prod`) REFERENCES `usuario` (`Id_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `producto_detalle`
--
ALTER TABLE `producto_detalle`
  ADD CONSTRAINT `Producto-Material-Detalle` FOREIGN KEY (`Id_Material_Prod_Detalle`) REFERENCES `material` (`Id_Material`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Producto-Producto` FOREIGN KEY (`Id_Producto_Prod`) REFERENCES `producto` (`Id_Producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `producto_temp`
--
ALTER TABLE `producto_temp`
  ADD CONSTRAINT `Producto-Material-Temp` FOREIGN KEY (`Id_Material_Prod_temp`) REFERENCES `material` (`Id_Material`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Producto-Usuario-Temp` FOREIGN KEY (`Id_Usuario_Prod_temp`) REFERENCES `usuario` (`Id_Usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `Rol-Usuario` FOREIGN KEY (`Id_Rol_Us`) REFERENCES `rolusuario` (`Id_Rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
