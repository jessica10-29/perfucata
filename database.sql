CREATE DATABASE IF NOT EXISTS perfucata;
USE perfucata;

DROP TABLE IF EXISTS perfumeria_total;
CREATE TABLE perfumeria_total (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo ENUM('admin', 'categoria', 'producto', 'blog') NOT NULL,
    nombre_titulo VARCHAR(255),
    clave_imagen VARCHAR(255),
    contenido_texto TEXT,
    precio DECIMAL(10, 2) DEFAULT 0.00,
    descuento DECIMAL(5, 2) DEFAULT 0.00,
    destacado TINYINT(1) DEFAULT 0,
    recomendado TINYINT(1) DEFAULT 0,
    estado VARCHAR(50),
    relacion_id INT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO perfumeria_total (tipo, nombre_titulo, clave_imagen) VALUES
('admin', 'admin', '1234');

INSERT INTO perfumeria_total (tipo, nombre_titulo) VALUES
('categoria', 'Masculinos'),
('categoria', 'Femeninos'),
('categoria', 'Infantil');

SET @cat_masculinos = (SELECT id FROM perfumeria_total WHERE tipo='categoria' AND nombre_titulo='Masculinos' LIMIT 1);
SET @cat_femeninos  = (SELECT id FROM perfumeria_total WHERE tipo='categoria' AND nombre_titulo='Femeninos' LIMIT 1);
SET @cat_infantil   = (SELECT id FROM perfumeria_total WHERE tipo='categoria' AND nombre_titulo='Infantil' LIMIT 1);

INSERT INTO perfumeria_total (tipo, nombre_titulo, clave_imagen, contenido_texto) VALUES
('blog', 'Secretos de Duracion', 'https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=800', 'Para que tu fragancia dure mas, aplicala en puntos de pulso.');

-- Femeninos (10) con imagenes locales reales de frascos
INSERT INTO perfumeria_total (tipo, nombre_titulo, contenido_texto, precio, clave_imagen, relacion_id, estado) VALUES
('producto', 'Chanel No. 5 Eau de Parfum 100ml', 'Floral aldehidico clasico con rosa, ylang-ylang y fondo avainillado. Una referencia elegante y atemporal para mujer.', 829990.00, 'images/perfumes/femeninos/chanel-no5.jpg', @cat_femeninos, 'Disponible'),
('producto', 'Chanel No. 5 Eau de Parfum 50ml', 'Version femenina de perfil sofisticado, con salida limpia y un corazon floral muy reconocible.', 629990.00, 'images/perfumes/femeninos/chanel-no5.jpg', @cat_femeninos, 'Disponible'),
('producto', 'Dior Jadore Eau de Parfum 100ml', 'Bouquet floral luminoso con jazmin, rosa y acordes suaves que dejan una estela femenina y elegante.', 739900.00, 'images/perfumes/femeninos/dior-jadore.jpg', @cat_femeninos, 'Disponible'),
('producto', 'Dior Jadore Eau de Parfum 50ml', 'Fragancia floral radiante, ideal para quien busca un perfume clasico, refinado y facil de reconocer.', 559900.00, 'images/perfumes/femeninos/dior-jadore.jpg', @cat_femeninos, 'Disponible'),
('producto', 'Givenchy Irresistible Eau de Parfum 80ml', 'Rosa suave, notas afrutadas y almizcles delicados en un perfume moderno, femenino y muy limpio.', 390000.00, 'images/perfumes/femeninos/givenchy-irresistible.jpg', @cat_femeninos, 'Disponible'),
('producto', 'Givenchy LInterdit Eau de Parfum 80ml', 'Azahar, nardo y fondo amaderado en una composicion elegante, intensa y muy femenina.', 460000.00, 'images/perfumes/femeninos/givenchy-linterdit.jpg', @cat_femeninos, 'Disponible'),
('producto', 'Carolina Herrera 212 Sexy Women 100ml', 'Perfume calido con vainilla, almizcle y un toque dulce que se siente sensual y sofisticado.', 469900.00, 'images/perfumes/femeninos/carolina-herrera-212-sexy.jpg', @cat_femeninos, 'Disponible'),
('producto', 'Carolina Herrera 212 Sexy Women 60ml', 'Version practica del 212 Sexy, con perfil femenino, dulce y elegante para dia o noche.', 359900.00, 'images/perfumes/femeninos/carolina-herrera-212-sexy.jpg', @cat_femeninos, 'Disponible'),
('producto', 'Avon Rare Pearls Eau de Parfum 50ml', 'Floral suave con matices empolvados y un acabado delicado para uso diario.', 44900.00, 'images/perfumes/femeninos/avon-rare-pearls.jpg', @cat_femeninos, 'Disponible'),
('producto', 'Avon Rare Pearls Eau de Parfum 100ml', 'Aroma femenino clasico con sensacion limpia, elegante y ligera para cualquier momento.', 79900.00, 'images/perfumes/femeninos/avon-rare-pearls.jpg', @cat_femeninos, 'Disponible');

-- Masculinos (10) con imagenes locales reales de frascos
INSERT INTO perfumeria_total (tipo, nombre_titulo, contenido_texto, precio, clave_imagen, relacion_id, estado) VALUES
('producto', 'Dior Sauvage Eau de Toilette 100ml', 'Bergamota fresca, pimienta y fondo ambarado en una fragancia masculina intensa y moderna.', 699000.00, 'images/perfumes/masculinos/dior-sauvage.jpg', @cat_masculinos, 'Disponible'),
('producto', 'Dior Sauvage Eau de Toilette 60ml', 'Formato ideal para uso diario con salida citrica, presencia elegante y secado limpio.', 499900.00, 'images/perfumes/masculinos/dior-sauvage.jpg', @cat_masculinos, 'Disponible'),
('producto', 'Acqua di Gio Pour Homme 100ml', 'Acorde marino, citrico y limpio que transmite frescura masculina y mucha versatilidad.', 429990.00, 'images/perfumes/masculinos/acqua-di-gio.jpg', @cat_masculinos, 'Disponible'),
('producto', 'Acqua di Gio Pour Homme 50ml', 'Version fresca y ligera del clasico masculino, perfecta para oficina y clima calido.', 339990.00, 'images/perfumes/masculinos/acqua-di-gio.jpg', @cat_masculinos, 'Disponible'),
('producto', 'Dior Homme Intense 100ml', 'Iris, cacao suave y maderas elegantes en un perfume masculino sofisticado y con presencia.', 685000.00, 'images/perfumes/masculinos/dior-homme-intense.jpg', @cat_masculinos, 'Disponible'),
('producto', 'Dior Homme Intense 50ml', 'Formato compacto con caracter refinado, fondo amaderado y salida moderna para hombre.', 519900.00, 'images/perfumes/masculinos/dior-homme-intense.jpg', @cat_masculinos, 'Disponible'),
('producto', 'CH Men Carolina Herrera 100ml', 'Notas dulces, cuero suave y maderas en un perfume masculino elegante y urbano.', 409000.00, 'images/perfumes/masculinos/ch-men.jpg', @cat_masculinos, 'Disponible'),
('producto', 'CH Men Carolina Herrera 50ml', 'Version practica con perfil calido y moderno, ideal para tarde o noche.', 319900.00, 'images/perfumes/masculinos/ch-men.jpg', @cat_masculinos, 'Disponible'),
('producto', 'Moon Water Men Eau de Parfum 100ml', 'Fragancia masculina fresca con vibra marina, limpia y juvenil para uso diario.', 169900.00, 'images/perfumes/masculinos/moon-water-men.jpg', @cat_masculinos, 'Disponible'),
('producto', 'For Men Eau de Toilette 100ml', 'Colonia masculina clasica con salida limpia y fondo suave para quien busca algo sencillo y funcional.', 149900.00, 'images/perfumes/masculinos/for-men-classic.jpg', @cat_masculinos, 'Disponible');

-- Infantil (10) con imagenes locales reales de colonias suaves
INSERT INTO perfumeria_total (tipo, nombre_titulo, contenido_texto, precio, clave_imagen, relacion_id, estado) VALUES
('producto', 'Johnsons Baby Cologne 200ml', 'Colonia infantil fresca y suave con aroma limpio, pensada para despues del bano.', 29700.00, 'images/perfumes/infantil/johnsons-baby-cologne.jpg', @cat_infantil, 'Disponible'),
('producto', 'Johnsons Baby Cologne 100ml', 'Presentacion practica de aroma delicado, ligero y agradable para uso diario.', 18900.00, 'images/perfumes/infantil/johnsons-baby-cologne.jpg', @cat_infantil, 'Disponible'),
('producto', 'Mustela Musti Eau de Soin 50ml', 'Fragancia infantil delicada con sensacion limpia, floral suave y muy respetuosa.', 84900.00, 'images/perfumes/infantil/mustela-musti.png', @cat_infantil, 'Disponible'),
('producto', 'Mustela Musti Fragancia Delicada 50ml', 'Aroma suave para bebe con salida fresca y perfil calmado, ideal para piel sensible.', 84900.00, 'images/perfumes/infantil/mustela-musti.png', @cat_infantil, 'Disponible'),
('producto', 'Nenuco Original Agua de Colonia 650ml', 'Clasico aroma infantil citrico y limpio, muy reconocido para el cuidado diario.', 59900.00, 'images/perfumes/infantil/nenuco-original.jpg', @cat_infantil, 'Disponible'),
('producto', 'Nenuco Original Agua de Colonia 240ml', 'Version mediana del aroma tradicional infantil con sensacion fresca y familiar.', 32900.00, 'images/perfumes/infantil/nenuco-original.jpg', @cat_infantil, 'Disponible'),
('producto', 'Johnsons Baby Cologne Fresh 200ml', 'Fragancia infantil muy suave con sensacion de limpieza y frescura despues del bano.', 29700.00, 'images/perfumes/infantil/johnsons-baby-cologne.jpg', @cat_infantil, 'Disponible'),
('producto', 'Mustela Musti Agua Suave 50ml', 'Colonia ligera para bebe con perfil fino, limpio y calmante.', 84900.00, 'images/perfumes/infantil/mustela-musti.png', @cat_infantil, 'Disponible'),
('producto', 'Nenuco Original Familiar 650ml', 'Colonia infantil tradicional con toque citrico y caracter fresco para toda la familia.', 59900.00, 'images/perfumes/infantil/nenuco-original.jpg', @cat_infantil, 'Disponible'),
('producto', 'Johnsons Baby Cologne Suave 100ml', 'Presentacion pequena con aroma infantil limpio y muy agradable para uso cotidiano.', 18900.00, 'images/perfumes/infantil/johnsons-baby-cologne.jpg', @cat_infantil, 'Disponible');
