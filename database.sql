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

-- Femeninos (10)
INSERT INTO perfumeria_total (tipo, nombre_titulo, contenido_texto, precio, clave_imagen, relacion_id, estado) VALUES
('producto', 'Chanel No. 5', 'Iconico floral aldehido con un perfil elegante y atemporal.', 150.00, 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible'),
('producto', 'La Vie Est Belle', 'Iris, praline y vainilla con salida dulce y femenina.', 110.00, 'https://images.unsplash.com/photo-1525286116112-b59af11adad1?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible'),
('producto', 'Good Girl', 'Cacao, jazmin y haba tonka en una estela sensual.', 125.00, 'https://images.unsplash.com/photo-1524594154908-edd335c7e4c5?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible'),
('producto', 'YSL Libre', 'Lavanda, azahar y vainilla en un floral moderno.', 130.00, 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible'),
('producto', 'Jadore', 'Bouquet floral luminoso con notas suaves y elegantes.', 145.00, 'https://images.unsplash.com/photo-1506617420156-8e4536971650?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible'),
('producto', 'Gucci Bloom', 'Tuberosa y jazmin natural con aire fresco y limpio.', 105.00, 'https://images.unsplash.com/photo-1515377906489-17c72b32e99f?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible'),
('producto', 'Bright Crystal', 'Granada, peonia y magnolia con firma fresca.', 85.00, 'https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible'),
('producto', 'Flowerbomb', 'Explosion floral gourmand con fondo dulce.', 140.00, 'https://images.unsplash.com/photo-1498842812179-c81beecf902c?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible'),
('producto', 'Daisy', 'Fresco juvenil con fresia, violeta y frutas suaves.', 95.00, 'https://images.unsplash.com/photo-1518834107812-67b0b7c58434?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible'),
('producto', 'Burberry Her', 'Grosella negra y ambar con toque afrutado moderno.', 120.00, 'https://images.unsplash.com/photo-1527515545085-5db817172677?auto=format&fit=crop&w=900&q=80', @cat_femeninos, 'Disponible');

-- Masculinos (10)
INSERT INTO perfumeria_total (tipo, nombre_titulo, contenido_texto, precio, clave_imagen, relacion_id, estado) VALUES
('producto', 'Sauvage', 'Bergamota, pimienta y ambroxan con perfil intenso.', 115.00, 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=900&q=80', @cat_masculinos, 'Disponible'),
('producto', 'Bleu de Chanel', 'Citrico amaderado elegante y muy versatil.', 150.00, 'https://images.unsplash.com/photo-1512499617640-c2f999098c01?auto=format&fit=crop&w=900&q=80', @cat_masculinos, 'Disponible'),
('producto', 'Acqua di Gio', 'Acuatico fresco y limpio para uso diario.', 115.00, 'https://images.unsplash.com/photo-1506765515384-028b60a970df?auto=format&fit=crop&w=900&q=80', @cat_masculinos, 'Disponible'),
('producto', 'Invictus', 'Notas marinas y laurel con salida energetica.', 95.00, 'https://images.unsplash.com/photo-1527515545085-5db817172677?auto=format&fit=crop&w=900&q=80', @cat_masculinos, 'Disponible'),
('producto', 'One Million', 'Canela, cuero y ambar con presencia dulce.', 95.00, 'https://images.unsplash.com/photo-1523825036634-aab3f4fb0b66?auto=format&fit=crop&w=900&q=80', @cat_masculinos, 'Disponible'),
('producto', 'YSL Y', 'Salvia, lavanda y maderas con aire moderno.', 130.00, 'https://images.unsplash.com/photo-1541643600914-78b084683601?auto=format&fit=crop&w=900&q=80', @cat_masculinos, 'Disponible'),
('producto', 'Boss Bottled', 'Manzana, canela y vetiver con tono elegante.', 90.00, 'https://images.unsplash.com/photo-1489515217757-5fd1be406fef?auto=format&fit=crop&w=900&q=80', @cat_masculinos, 'Disponible'),
('producto', 'Versace Eros', 'Menta, manzana verde y vainilla con fuerza.', 95.00, 'https://images.unsplash.com/photo-1545239351-1141bd82e8a6?auto=format&fit=crop&w=901&q=80', @cat_masculinos, 'Disponible'),
('producto', 'Prada Luna Rossa', 'Lavanda fresca y ambar gris en un perfil limpio.', 110.00, 'https://images.unsplash.com/photo-1512499617640-c2f999098c01?auto=format&fit=crop&w=901&q=80', @cat_masculinos, 'Disponible'),
('producto', 'Montblanc Explorer', 'Bergamota y vetiver con fondo amaderado.', 85.00, 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=901&q=80', @cat_masculinos, 'Disponible');

-- Infantiles (10)
INSERT INTO perfumeria_total (tipo, nombre_titulo, contenido_texto, precio, clave_imagen, relacion_id, estado) VALUES
('producto', 'Baby Tous', 'Colonia suave para bebe con aroma limpio.', 30.00, 'https://images.unsplash.com/photo-1500964757637-c85e8a162699?auto=format&fit=crop&w=900&q=80', @cat_infantil, 'Disponible'),
('producto', 'Mustela Musti', 'Aroma delicado pensado para piel sensible.', 22.00, 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=900&q=80', @cat_infantil, 'Disponible'),
('producto', 'Nenuco Original', 'Citrico suave y muy fresco para ninos.', 12.00, 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?auto=format&fit=crop&w=902&q=80', @cat_infantil, 'Disponible'),
('producto', 'Petit Cheri', 'Floral ligero infantil con salida suave.', 18.00, 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=901&q=80', @cat_infantil, 'Disponible'),
('producto', '1916 Bebe', 'Colonia tradicional fresca para uso diario.', 16.00, 'https://images.unsplash.com/photo-1500964757637-c85e8a162699?auto=format&fit=crop&w=901&q=80', @cat_infantil, 'Disponible'),
('producto', 'Frozen Kids', 'Notas dulces frutales con aroma ligero.', 18.00, 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?auto=format&fit=crop&w=902&q=80', @cat_infantil, 'Disponible'),
('producto', 'Hello Kitty Pink', 'Frutal suave con toque divertido.', 17.00, 'https://images.unsplash.com/photo-1500964757637-c85e8a162699?auto=format&fit=crop&w=906&q=80', @cat_infantil, 'Disponible'),
('producto', 'Baby Magic Soft', 'Aroma a talco muy suave y limpio.', 15.00, 'https://images.unsplash.com/photo-1500964757637-c85e8a162699?auto=format&fit=crop&w=907&q=80', @cat_infantil, 'Disponible'),
('producto', 'Chicco Baby Moments', 'Notas calmantes y limpias para bebe.', 19.00, 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?auto=format&fit=crop&w=903&q=80', @cat_infantil, 'Disponible'),
('producto', 'Johnsons Baby Cologne', 'Clasico aroma infantil suave y fresco.', 10.00, 'https://images.unsplash.com/photo-1500964757637-c85e8a162699?auto=format&fit=crop&w=904&q=80', @cat_infantil, 'Disponible');
