ALTER TABLE galeri
ADD CONSTRAINT fk_admin
FOREIGN KEY (id_admin) REFERENCES admin(id_admin);