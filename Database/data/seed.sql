SET NAMES utf8mb4;
USE elyra;

-- Insertar géneros de anime en la tabla generos
INSERT IGNORE INTO generos (nombre_genero) VALUES 
('Acción'),
('Aventura'),
('Comedia'),
('Romance'),
('Drama'),
('Fantasía'),
('Ciencia Ficción'),
('Sobrenatural'),
('Misterio'),
('Terror'),
('Escolar'),
('Slice of Life'),
('Deportes'),
('Isekai'),
('Mecha'),
('Psicología'),
('Suspenso');

-- ============================================================
-- CONTENIDO INICIAL AGREGADO MANUALMENTE (ID 1 al 3)
-- ============================================================
INSERT INTO peliculas_series (
    titulo,
    titulo_original,
    descripcion,
    tipo,
    estado,
    estado_emision,
    anio_lanzamiento,
    fecha_estreno,
    duracion_minutos,
    temporadas,
    episodios,
    imagen_portada,
    imagen_banner,
    trailer_url,
    destacado,
    activo
) VALUES
('Your Name', 'Kimi no Na wa.',
'Mitsuha Miyamizu es una estudiante que vive en un tranquilo pueblo rural y sueña con experimentar la vida en Tokio. Taki Tachibana, por otro lado, es un joven estudiante de la ciudad que lleva una rutina ocupada entre sus clases, su trabajo de medio tiempo y sus amigos. Un día, ambos comienzan a despertar en el cuerpo del otro sin entender la razón, compartiendo recuerdos, emociones y momentos de vidas completamente distintas. Mientras intentan adaptarse a este extraño vínculo, descubren que su conexión va mucho más allá de un simple intercambio y que el tiempo, la distancia y el destino esconden una verdad capaz de cambiarlo todo.',
'Película', 'Publicado', 'Finalizado', 2016, '2016-08-26', 106, 0, 1,
'library/contenido/portadas/yourname.webp',
'library/contenido/banners/yourname.webp',
'https://www.youtube.com/watch?v=ayi6VfCKBcA', 1, 1),

('Golden Time', 'Golden Time',
'Banri Tada llega a Tokio para comenzar su vida universitaria después de haber perdido sus recuerdos a causa de un accidente. En este nuevo entorno intenta construir una identidad desde cero, conocer amigos y dejar atrás la confusión de su pasado. Durante su primer día conoce a Mitsuo Yanagisawa y a Koko Kaga, una joven elegante, intensa y decidida cuya presencia pronto altera la tranquilidad que Banri intenta alcanzar. A medida que se involucra con sus compañeros y enfrenta sentimientos cada vez más complejos, Banri deberá descubrir quién es realmente, qué significa amar y cómo vivir con los recuerdos que poco a poco comienzan a regresar.',
'Serie', 'Publicado', 'Finalizado', 2013, '2013-10-04', 24, 1, 24,
'library/contenido/portadas/goldentime.jpg',
'library/contenido/banners/goldentime.png',
'https://www.youtube.com/watch?v=44njDYJ5OJA&list=RD44njDYJ5OJA&start_radio=1', 1, 1),

('Toradora', 'Toradora',
'Toradora es una comedia romántica y drama que sigue a Ryuji, un joven de aspecto intimidante pero amable, y Taiga, una chica diminuta de carácter feroz. Tras descubrir que están enamorados de sus respectivos mejores amigos, forman una alianza para ayudarse a confesar sus sentimientos, lo que termina uniéndolos.',
'Serie', 'Publicado', 'Finalizado', 2008, '2008-02-10', 24, 1, 25,
'library/contenido/portadas/toradora-20260621013430-9874.jpg',
'library/contenido/banners/toradora-baner-20260621013430-3089.jpg',
'https://youtu.be/ya570uUgQNc?si=e8bGUXekZ5hQsN-k', 1, 1);

-- ============================================================
-- INSERCIÓN DE 50 PELÍCULAS ANIME (ID 4 al 53)
-- ============================================================
INSERT INTO peliculas_series (titulo, titulo_original, descripcion, tipo, estado, estado_emision, anio_lanzamiento, fecha_estreno, duracion_minutos, temporadas, episodios) VALUES

('Akira', 'Akira',
'En el año 2019, Neo-Tokio es una ciudad devastada por una explosión misteriosa que desencadenó la Tercera Guerra Mundial. Treinta y un años después, la ciudad ha sido reconstruida, pero la corrupción y la violencia están a la orden del día. Shotaro Kaneda es el líder de una pandilla de motociclistas, y su mejor amigo Tetsuo Shima desarrolla poderes psicoquinéticos después de un accidente con un niño esper que escapó de un laboratorio gubernamental. A medida que los poderes de Tetsuo crecen sin control, el gobierno y una organización secreta intentan contenerlo, desencadenando una espiral de destrucción que amenaza con destruir toda la ciudad. Una obra maestra de la ciencia ficción que explora temas como el poder, la amistad y la autodestrucción.',
'Película', 'Publicado', 'Finalizado', 1988, '1988-07-16', 124, NULL, NULL),

('Ghost in the Shell', 'Ghost in the Shell',
'En un futuro cyberpunk, la mayor parte de la humanidad está conectada a través de redes informáticas y muchas personas tienen implantes cibernéticos. La Mayor Motoko Kusanagi es una agente de la Sección 9 de seguridad pública que persigue al enigmático Puppet Master, un hacker que resulta ser una inteligencia artificial con conciencia propia. A lo largo de la investigación, Kusanagi cuestiona su propia identidad y lo que significa ser humano en un mundo donde los cuerpos pueden ser reemplazados. Una película fundamental del género ciberpunk que inspiró obras como The Matrix.',
'Película', 'Publicado', 'Finalizado', 1995, '1995-11-18', 83, NULL, NULL),

('Princess Mononoke', 'Mononoke-hime',
'El príncipe Ashitaka es maldecido por un demonio mientras protege su aldea y se ve obligado a viajar hacia el oeste en busca de una cura. En su viaje se encuentra con la princesa Mononoke, una joven criada por lobos que lucha contra Lady Eboshi y los habitantes de la Ciudad del Hierro, quienes están destruyendo el bosque. Ashitaka se encuentra atrapado entre los dioses del bosque y los humanos, tratando de encontrar la paz entre ambos bandos. Una epopeya animada que aborda temas ambientales, la industrialización y la convivencia entre naturaleza y humanidad.',
'Película', 'Publicado', 'Finalizado', 1997, '1997-07-12', 134, NULL, NULL),

('Spirited Away', 'Sen to Chihiro no Kamikakushi',
'Chihiro es una niña de diez años que se muda con sus padres. En el camino encuentran un túnel que los lleva a un mundo espiritual donde sus padres son transformados en cerdos por comer la comida de los dioses. Chihiro debe trabajar en la casa de baños de la bruja Yubaba para salvar a sus padres y encontrar el camino de regreso al mundo humano. Con la ayuda de Haku y otros espíritus, aprende sobre la valentía, la amistad y la importancia de recordar quién eres. La película más famosa del Studio Ghibli, ganadora del Oscar a Mejor Película Animada.',
'Película', 'Publicado', 'Finalizado', 2001, '2001-07-20', 125, NULL, NULL),

('Howl''s Moving Castle', 'Hauru no Ugoku Shiro',
'Sophie es una joven sombrerera transformada en anciana por la Bruja del Páramo. Buscando una solución, termina en el castillo ambulante del mago Howl, un joven talentoso envuelto en una guerra entre reinos. Dentro del castillo conoce a Calcifer, un demonio de fuego, y a Markl, el aprendiz de Howl. A medida que Sophie se adapta a su nueva vida, descubre los secretos de Howl y se involucra en el conflicto bélico. Una historia mágica sobre el amor, la autoestima y la guerra, basada en la novela de Diana Wynne Jones.',
'Película', 'Publicado', 'Finalizado', 2004, '2004-11-20', 119, NULL, NULL),

('My Neighbor Totoro', 'Tonari no Totoro',
'Dos hermanas, Satsuki y Mei, se mudan al campo para estar cerca del hospital donde su madre se recupera. Mientras exploran los alrededores descubren que el bosque está habitado por criaturas mágicas llamadas Totoro, espíritus que solo los niños pueden ver. Mei se encuentra con un Totoro pequeño y lo sigue hasta el árbol donde conoce al Totoro gigante. Juntos viven aventuras mágicas, incluyendo un viaje nocturno en el Gatobús. Una película tierna que captura la magia de la infancia y la conexión con la naturaleza.',
'Película', 'Publicado', 'Finalizado', 1988, '1988-04-16', 86, NULL, NULL),

('The Wind Rises', 'Kaze Tachinu',
'Jiro Horikoshi es un joven apasionado por la aviación que sueña con diseñar aviones. A pesar de ser miope, estudia ingeniería aeronáutica y se convierte en un talentoso diseñador. Mientras desarrolla el caza Mitsubishi A6M Zero, Jiro se enamora de Naoko Satomi, una joven con tuberculosis. La película sigue su relación mientras él persigue su sueño de crear el avión perfecto en medio de las tensiones de la guerra. Una reflexión sobre la creatividad, el amor y las consecuencias de la innovación tecnológica.',
'Película', 'Publicado', 'Finalizado', 2013, '2013-07-20', 126, NULL, NULL),

('Nausicaä of the Valley of the Wind', 'Kaze no Tani no Naushika',
'En un mundo postapocalíptico, el Mar de la Descomposición es un bosque tóxico lleno de esporas letales e insectos gigantes. Nausicaä, princesa del Valle del Viento, tiene la capacidad única de comunicarse con los insectos gigantes. Cuando un imperio vecino despierta a un antiguo guerrero gigante para destruir el bosque, Nausicaä debe arriesgar todo para proteger a su pueblo y al medio ambiente. Una poderosa historia ecológica que estableció los temas recurrentes del Studio Ghibli.',
'Película', 'Publicado', 'Finalizado', 1984, '1984-03-11', 117, NULL, NULL),

('Castle in the Sky', 'Tenkū no Shiro Rapyuta',
'Sheeta, una joven huérfana, es perseguida por agentes del gobierno y piratas del aire por un colgante misterioso que pertenece a la legendaria isla flotante de Laputa. Conoce a Pazu, un chico que trabaja en una mina, y juntos emprenden un viaje para encontrar la isla antes que el malvado Coronel Muska. Descubren que Laputa es una ciudad avanzada tecnológicamente con un poder inmenso. Una emocionante aventura steampunk sobre la amistad y el peligro del poder desmedido.',
'Película', 'Publicado', 'Finalizado', 1986, '1986-08-02', 124, NULL, NULL),

('Kiki''s Delivery Service', 'Majo no Takkyūbin',
'Kiki es una joven bruja que debe dejar su hogar un año para vivir sola. Vuela con su gato Jiji hacia la ciudad costera de Koriko, donde inicia un servicio de entregas a domicilio. Kiki enfrenta los desafíos de la vida independiente, la soledad, la inseguridad y la pérdida temporal de sus poderes mágicos. Una historia encantadora sobre el crecimiento personal, la independencia y encontrar tu lugar en el mundo.',
'Película', 'Publicado', 'Finalizado', 1989, '1989-07-29', 102, NULL, NULL),

('Perfect Blue', 'Pafekuto Buru',
'Mima Kirigoe, exmiembro del grupo pop CHAM, se retira para ser actriz. Su decisión enfurece a un acosador obsesivo que crea un sitio web detallando sus movimientos. Al asumir papeles desafiantes, Mima pierde la noción de la realidad mientras personas a su alrededor mueren asesinadas. Ya no sabe si es la perpetradora o la próxima víctima. Un thriller psicológico magistral sobre la identidad, la fama y la delgada línea entre la realidad y la ficción.',
'Película', 'Publicado', 'Finalizado', 1997, '1997-07-25', 81, NULL, NULL),

('Paprika', 'Papurika',
'La Dra. Chiba Atsuko trabaja con el DC Mini, un dispositivo que permite entrar en los sueños de los pacientes. En el mundo onírico adopta la identidad de Paprika. Cuando el dispositivo es robado, alguien fusiona los sueños con la realidad causando caos. Paprika debe adentrarse en un laberinto onírico para detener al responsable. Una obra visualmente deslumbrante del director Satoshi Kon.',
'Película', 'Publicado', 'Finalizado', 2006, '2006-11-25', 90, NULL, NULL),

('Summer Wars', 'Samā Wōzu',
'Kenji Koiso, un genio matemático, es invitado por Natsuki a pasar el verano en la finca de su familia. Allí se ve envuelto en un conflicto global cuando resuelve un código que desencadena el caos en OZ, un mundo virtual. Love Machine, una inteligencia artificial, hackea sistemas en todo el mundo. Kenji y la familia Shinohara deben unirse para detenerlo. Una emocionante mezcla de drama familiar y ciencia ficción.',
'Película', 'Publicado', 'Finalizado', 2009, '2009-08-01', 114, NULL, NULL),

('The Boy and the Beast', 'Bakemono no Ko',
'Ren, un niño que huye de la custodia tras la muerte de su madre, termina en el mundo de las bestias Jutengai. Allí conoce a Kumatetsu, un oso bestia que busca un aprendiz. A regañadientes, Kumatetsu entrena a Ren, llamado Kyuta. Con los años desarrollan un vínculo profundo. Cuando Kyuta crece, debe elegir entre dos mundos. Una conmovedora historia sobre la paternidad, el crecimiento y la búsqueda de pertenencia.',
'Película', 'Publicado', 'Finalizado', 2015, '2015-07-11', 119, NULL, NULL),

('Wolf Children', 'Ōkami Kodomo no Ame to Yuki',
'Hana, estudiante universitaria, se enamora de un hombre lobo y tienen dos hijos, Ame y Yuki. Cuando su pareja muere, Hana debe criar sola a sus hijos especiales que pueden transformarse en lobos. Se muda a un pueblo remoto en las montañas, donde aprende a cultivar la tierra y criar a sus hijos en armonía con la naturaleza. Una historia emotiva sobre la maternidad, el sacrificio y la aceptación de quienes somos.',
'Película', 'Publicado', 'Finalizado', 2012, '2012-07-21', 117, NULL, NULL),

('The Girl Who Leapt Through Time', 'Toki o Kakeru Shōjo',
'Makoto Konno descubre que tiene la capacidad de saltar en el tiempo. Usa su habilidad para mejorar su vida cotidiana: evitar llegar tarde, mejorar notas y pasar tiempo con amigos. Cada salto temporal tiene consecuencias imprevistas que afectan a quienes la rodean, especialmente a sus amigos Chiaki y Kousuke. Makoto aprende que no se puede jugar con el tiempo sin pagar un precio. Una historia nostálgica sobre la adolescencia y el arrepentimiento.',
'Película', 'Publicado', 'Finalizado', 2006, '2006-07-15', 98, NULL, NULL),

('5 Centimeters per Second', 'Byōsoku 5 Senchimētoru',
'Takaki Tōno y Akari Shinohara son amigos en la escuela primaria que se separan cuando la familia de Akari se muda. Años intentan mantener su amistad mediante cartas, pero la distancia los separa. La película en tres segmentos muestra diferentes etapas de sus vidas, desde la infancia hasta la adultez donde han seguido caminos separados. Una reflexión poética sobre el amor perdido, la distancia y el paso del tiempo.',
'Película', 'Publicado', 'Finalizado', 2007, '2007-03-03', 63, NULL, NULL),

('Children Who Chase Lost Voices', 'Hoshi o Ou Kodomo',
'Asuna Watase, una joven solitaria, escucha transmisiones misteriosas. Conoce a Shun, un chico del mundo subterráneo de Agartha donde los muertos pueden revivir. Cuando Shun desaparece, Asuna viaja a Agartha con su maestro Morisaki, quien busca revivir a su esposa. Descubren un mundo de criaturas extraordinarias. Una aventura épica sobre el duelo, la esperanza y el dejar ir.',
'Película', 'Publicado', 'Finalizado', 2011, '2011-05-07', 116, NULL, NULL),

('I Want to Eat Your Pancreas', 'Kimi no Suizō o Tabetai',
'Un estudiante introvertido encuentra el diario de Sakura Yamauchi, quien sufre una enfermedad pancreática terminal. A pesar de sus personalidades opuestas, deciden pasar juntos el tiempo que le queda. Sakura lo ayuda a salir de su caparazón mientras él le ofrece compañía sin lástima. Aprenden sobre la vida, la muerte y la importancia de las conexiones humanas. Una historia desgarradora sobre el valor de cada momento.',
'Película', 'Publicado', 'Finalizado', 2018, '2018-09-01', 108, NULL, NULL),

('A Silent Voice', 'Koe no Katachi',
'Shoya Ishida acosaba a Shoko Nishimiya, una compañera sorda. Sus acciones lo convierten en el acosado y termina aislado. Años después, atormentado por la culpa, busca a Shoko para disculparse. Lo que comienza como un intento de redención se convierte en una amistad que ayuda a ambos a sanar. Una película conmovedora sobre el perdón, la discapacidad y la posibilidad de cambio.',
'Película', 'Publicado', 'Finalizado', 2016, '2016-09-17', 129, NULL, NULL),

('Weathering with You', 'Tenki no Ko',
'Hodaka huye de su hogar a Tokio, donde conoce a Hina, una joven que puede controlar el clima despejando el cielo al rezar. Usan el poder de Hina para ofrecer días soleados, pero descubren que tiene un precio terrible. Una historia romántica y fantástica sobre el sacrificio, el amor y la lucha contra el destino.',
'Película', 'Publicado', 'Finalizado', 2019, '2019-07-19', 112, NULL, NULL),

('Suzume', 'Suzume no Tojimari',
'Suzume conoce a Souta, un joven que busca puertas abandonadas para cerrarlas y evitar que gusanos gigantes causen terremotos. Cuando Souta es transformado en silla, Suzume viaja por Japón cerrando puertas mientras enfrenta recuerdos de su infancia. Una aventura que combina folclore japonés con una historia de sanación personal.',
'Película', 'Publicado', 'Finalizado', 2022, '2022-11-11', 122, NULL, NULL),

('Belle', 'Ryū to Sobakasu no Hime',
'Suzu, una adolescente que perdió la alegría de cantar tras la muerte de su madre, se transforma en Belle en el mundo virtual de U, convirtiéndose en una sensación mundial. Conoce a una bestia misteriosa y debe descubrir su identidad real mientras sana sus propias heridas. Una versión moderna de La Bella y la Bestia en el mundo digital.',
'Película', 'Publicado', 'Finalizado', 2021, '2021-07-16', 122, NULL, NULL),

('In This Corner of the World', 'Kono Sekai no Katasumi ni',
'Suzu Urano se muda a Kure tras casarse en 1944. A medida que la guerra se intensifica, enfrenta escasez, bombardeos y relaciones familiares difíciles. Encuentra alegría en lo cotidiano: dibujar, cocinar con lo justo y las conexiones con su nueva familia. Una representación honesta de la vida civil durante la guerra, mostrando la resiliencia del espíritu humano.',
'Película', 'Publicado', 'Finalizado', 2016, '2016-11-12', 129, NULL, NULL),

('The Anthem of the Heart', 'Kokoro ga Sakebitagatterunda',
'Jun Naruse, silenciada por un hechizo tras revelar un secreto familiar, es seleccionada para el comité cultural de su escuela. Junto a otros estudiantes problemáticos debe organizar un musical. A través de la música encuentra una nueva forma de expresarse y enfrenta los traumas del pasado. Una historia sobre encontrar tu voz.',
'Película', 'Publicado', 'Finalizado', 2015, '2015-09-19', 119, NULL, NULL),

('Millennium Actress', 'Sennen Joyū',
'El documentalista Genya Tachibana entrevista a Chiyoko Fujiwara, leyenda del cine retirada. Mientras ella cuenta su vida, la entrevista se vuelve un viaje surrealista a través de sus películas. En todas busca a un hombre misterioso que conoció en su juventud. La línea entre realidad y cine se desvanece. Una reflexión sobre el amor, el arte y la memoria.',
'Película', 'Publicado', 'Finalizado', 2001, '2001-07-28', 87, NULL, NULL),

('Tokyo Godfathers', 'Tōkyō Goddofāzāzu',
'Gin, Hana y Miyuki, tres personas sin hogar en Tokio, encuentran un bebé abandonado en Navidad. Deciden buscar a sus padres, lo que los lleva a una serie de aventuras por la ciudad. En el camino cada uno enfrenta su pasado y encuentra redención. Una historia conmovedora sobre la familia, la esperanza y la bondad humana.',
'Película', 'Publicado', 'Finalizado', 2003, '2003-11-08', 91, NULL, NULL),

('Patema Inverted', 'Patema Inverted',
'En un mundo donde la gravedad funciona al revés para algunos, Patema conoce a Age, un joven de la superficie. Juntos navegan un mundo peligroso donde ella puede caer hacia el cielo. Huyen de autoridades que consideran a los invertidos una amenaza. Descubren la verdad sobre su mundo. Una historia de amor y aventura que desafía las perspectivas.',
'Película', 'Publicado', 'Finalizado', 2013, '2013-11-09', 99, NULL, NULL),

('The Secret World of Arrietty', 'Kari-gurashi no Arietti',
'Arrietty, una adolescente de diez centímetros, pertenece a una familia de Prestatarios que viven bajo una casa antigua tomando prestadas cosas de los humanos. Cuando Sho, un niño enfermo, la descubre, rompen la regla de no ser vistos. A pesar del peligro, desarrollan una amistad única. Una hermosa adaptación de la serie The Borrowers.',
'Película', 'Publicado', 'Finalizado', 2010, '2010-07-17', 94, NULL, NULL),

('From Up on Poppy Hill', 'Kokuriko-zaka Kara',
'En 1963, Umi Matsuzaki cuida su familia mientras su madre está fuera. Conoce a Shun Kazama, que lucha por salvar el club escolar de la demolición. Trabajan juntos y descubren una conexión inesperada en sus pasados. Una historia nostálgica sobre la juventud, el cambio y el primer amor en el Japón de posguerra.',
'Película', 'Publicado', 'Finalizado', 2011, '2011-07-16', 91, NULL, NULL),

('When Marnie Was There', 'Omoide no Mānī',
'Anna, una niña solitaria con asma, pasa el verano en el campo con sus tíos. Descubre una mansión abandonada donde conoce a Marnie, una niña misteriosa que se vuelve su única amiga. Anna descubre secretos del pasado de Marnie mientras aprende a aceptarse a sí misma. Una historia emotiva sobre la amistad, la identidad y los lazos familiares.',
'Película', 'Publicado', 'Finalizado', 2014, '2014-07-19', 103, NULL, NULL),

('The Tale of the Princess Kaguya', 'Kaguya-hime no Monogatari',
'Una princesa es encontrada dentro de un tallo de bambú por un anciano. La crían como su hija y descubre oro y telas, interpretándolo como señal de que es princesa. Se mudan a la capital, donde es cortejada por nobles. Pero Kaguya anhela su vida simple en el campo y guarda un secreto sobre su origen. Una obra maestra con estilo artístico inspirado en la pintura tradicional japonesa.',
'Película', 'Publicado', 'Finalizado', 2013, '2013-11-23', 137, NULL, NULL),

('Only Yesterday', 'Omohide Poro Poro',
'Taeko Okajima, oficinista de 27 años, viaja al campo para ayudar en la cosecha. El viaje evoca recuerdos de su infancia en 1966. En el presente conoce a Toshio, un granjero que le muestra la belleza de la vida rural. Taeko reflexiona sobre quién se ha convertido. Una película cotidiana sobre el autodescubrimiento y las decisiones de vida.',
'Película', 'Publicado', 'Finalizado', 1991, '1991-07-20', 118, NULL, NULL),

('Ocean Waves', 'Umi ga Kikoeru',
'Taku reflexiona sobre su último año escolar en Kochi. Cuando Rikako, una estudiante transferida de Tokio, llega, causa revuelo. Taku y su amigo Yutaka se sienten atraídos por ella. A pesar de su actitud distante, Taku descubre sus vulnerabilidades, causando tensión con Yutaka. Un triángulo amoroso adolescente sobre la complejidad de las relaciones juveniles.',
'Película', 'Publicado', 'Finalizado', 1993, '1993-05-05', 72, NULL, NULL),

('Pom Poko', 'Heisei Tanuki Gassen Ponpoko',
'Los tanukis de Tama viven felices hasta que los humanos desarrollan urbanizaciones que destruyen su hogar. Usan habilidades de transformación para defenderse con ilusiones y trucos. La situación se vuelve desesperada y recurren a técnicas extremas. Una sátira ecológica que combina comedia y drama sobre la lucha de la naturaleza contra el desarrollo urbano.',
'Película', 'Publicado', 'Finalizado', 1994, '1994-07-16', 119, NULL, NULL),

('Whisper of the Heart', 'Mimi o Sumaseba',
'Shizuku, apasionada por la lectura, descubre que todos sus libros fueron leídos por Seiji Amasawa. Lo conoce y él sueña con ser lutier en Italia. Su dedicación la inspira a perseguir sus sueños literarios. Escribe una novela fantástica sobre El Barón, una estatua de gato. Una historia inspiradora sobre el amor juvenil y la perseverancia.',
'Película', 'Publicado', 'Finalizado', 1995, '1995-07-15', 111, NULL, NULL),

('The Cat Returns', 'Neko no Ongaeshi',
'Haru salva a un gato que resulta ser el Príncipe Lune del Reino de los Gatos. Como agradecimiento, los gatos la colman de regalos y la invitan a su reino, donde planean casarla con el príncipe. Con la ayuda del Barón Humbert, Haru debe escapar antes de que sea tarde. Una aventura fantástica sobre la confianza en uno mismo.',
'Película', 'Publicado', 'Finalizado', 2002, '2002-07-19', 75, NULL, NULL),

('Mary and the Witch''s Flower', 'Meari to Majo no Hana',
'Mary encuentra una flor misteriosa que le otorga poderes mágicos y la lleva a Endor College, una escuela de brujería. Descubre que la flor tiene consecuencias peligrosas y que la directora oculta secretos oscuros. Para salvar a un niño atrapado, Mary usa su ingenio y valentía. Una aventura mágica del estudio Ponoc.',
'Película', 'Publicado', 'Finalizado', 2017, '2017-07-08', 103, NULL, NULL),

('Fireworks', 'Uchiage Hanabi, Shita kara Miru ka? Yoko kara Miru ka?',
'Norimichi y Yusuke compiten por la atención de Nazuna. Cuando ella pide ayuda para escapar, los tres viven una aventura con una esfera que altera la realidad. Cada decisión de Norimichi reinicia el mundo, explorando diferentes resultados. Una historia romántica sobre el qué hubiera pasado.',
'Película', 'Publicado', 'Finalizado', 2017, '2017-08-18', 90, NULL, NULL),

('The Boy and the Heron', 'Kimitachi wa Dō Ikiru ka',
'Mahito pierde a su madre durante la guerra. Se muda al campo con su padre y Natsuko. Una garza real parlante lo manipula para explorar una torre misteriosa. Dentro descubre un mundo fantástico donde vivos y muertos coexisten. Debe enfrentar sus miedos para encontrar el regreso. La película más personal de Miyazaki, ganadora del Oscar 2024.',
'Película', 'Publicado', 'Finalizado', 2023, '2023-07-14', 124, NULL, NULL),

('Jin-Roh: The Wolf Brigade', 'Jin-Rō',
'En una línea temporal alternativa, Kazuki Fuse es miembro de una fuerza antiterrorista de élite. Durante una operación se congela al ver a una joven terrorista. Conoce a Kei, su hermana, y comienza una relación peligrosa mientras es investigado por sus superiores. Una adaptación oscura y madura con fuertes elementos políticos y psicológicos.',
'Película', 'Publicado', 'Finalizado', 1999, '1999-01-22', 102, NULL, NULL),

('Steamboy', 'Suchīmubōi',
'Ray Steam recibe un paquete de su abuelo con una Steam Ball, un dispositivo de energía de vapor increíble. Es perseguido por la Fundación O''Hara que quiere el dispositivo para fines militares. Ray debe protegerlo mientras descubre los peligros de la tecnología descontrolada. Una espectacular película steampunk del creador de Akira.',
'Película', 'Publicado', 'Finalizado', 2004, '2004-07-17', 126, NULL, NULL),

('Redline', 'Reddrain',
'Sweet JP es un piloto temerario que quiere ganar Redline, la carrera más peligrosa del universo. La competencia de este año es en Roboworld, cuyo gobierno militar promete destruir a los participantes. JP compite contra los mejores pilotos de la galaxia. Una película visualmente impresionante hecha a mano durante 10 años.',
'Película', 'Publicado', 'Finalizado', 2009, '2009-10-09', 102, NULL, NULL),

('Metropolis', 'Meteoroporisu',
'En una ciudad futurista dividida entre humanos y robots, el detective Ban llega con su sobrino Kenichi. Kenichi conoce a Tima, una robot creada para gobernar el mundo. Mientras el Duque Red planea usarla como arma, Kenichi intenta protegerla. Una adaptación del manga de Osamu Tezuka con banda sonora de jazz.',
'Película', 'Publicado', 'Finalizado', 2001, '2001-05-26', 108, NULL, NULL),

('Tekkonkinkreet', 'Tekkon Kinkurīto',
'Kuro y Shiro, hermanos huérfanos, protegen las calles de la Ciudad del Tesoro. Kuro es violento, Shiro es dulce e inocente. Cuando la Yakuza intenta apoderarse de la ciudad, deben defender su hogar. Pero la violencia de Kuro comienza a consumirlo. Una historia visualmente única sobre la infancia, la identidad y la pérdida de la inocencia.',
'Película', 'Publicado', 'Finalizado', 2006, '2006-12-23', 111, NULL, NULL),

('Sword of the Stranger', 'Sutorenjā: Mukō Hadan',
'Nanashi, un samurái sin nombre que ha abandonado su código, vaga por el Japón feudal. Protege a Kotaro, un niño que huye de la organización china Bai-Lan, a cambio de comida. Debe enfrentar a los guerreros más letales. Una película de samuráis con coreografías de lucha excepcionales.',
'Película', 'Publicado', 'Finalizado', 2007, '2007-09-29', 102, NULL, NULL),

('Vampire Hunter D: Bloodlust', 'Vanpaia Hantā D',
'D, un dhampiro cazavampiros, es contratado para rescatar a Charlotte, secuestrada por el vampiro Meier Link. También lo persiguen los hermanos Markus. D descubre que Charlotte huyó voluntariamente por amor. Una película gótica visualmente impresionante con atmósfera oscura y trágica.',
'Película', 'Publicado', 'Finalizado', 2000, '2000-08-25', 103, NULL, NULL),

('Ninja Scroll', 'Jūbē Ninpūchō',
'Jubei, un ninja mercenario envenenado, debe trabajar para un espía gubernamental para obtener el antídoto. Lo envía a enfrentar a los Ocho Dioses del Demonio de Kimon, ninjas con poderes sobrenaturales. Jubei forma alianza con Kagero mientras lucha contra enemigos letales. Una película de acción violenta clásica de los noventa.',
'Película', 'Publicado', 'Finalizado', 1993, '1993-06-05', 92, NULL, NULL),

('Cowboy Bebop: The Movie', 'Cowboy Bebop: Tengoku no Tobira',
'En Marte, un ataque terrorista con un virus biológico mata a cientos. El responsable, Vincent Volaju, busca venganza contra la sociedad que lo creó. Spike y la tripulación del Bebop lo persiguen por el sistema solar. Acción trepidante con la inconfundible banda sonora de jazz.',
'Película', 'Publicado', 'Finalizado', 2001, '2001-09-01', 115, NULL, NULL),

('Promare', 'Promare',
'Galo Thymos es bombero de Burning Rescue que combate incendios provocados por Quemadores. Su archienemigo es Lio Fotia, líder de Mad Burnish. Cuando una conspiración mayor sale a luz, deben unir fuerzas para salvar el mundo. Una explosión de color y acción del estudio Trigger.',
'Película', 'Publicado', 'Finalizado', 2019, '2019-05-24', 111, NULL, NULL);
-- ============================================================

-- ============================================================
-- INSERCIÓN DE 70 SERIES ANIME (ID 54 al 123)
-- ============================================================
INSERT INTO peliculas_series (titulo, titulo_original, descripcion, tipo, estado, estado_emision, anio_lanzamiento, fecha_estreno, duracion_minutos, temporadas, episodios) VALUES

('Attack on Titan Temp 1', 'Shingeki no Kyojin',
'La humanidad vive aterrorizada dentro de enormes murallas que la protegen de los Titanes. Eren Jaeger, su hermana Mikasa y su amigo Armin son testigos cuando un Titán Colosal rompe la muralla. Eren se une al Cuerpo de Exploración y descubre que él mismo posee el poder de convertirse en Titán. Una historia épica de supervivencia, libertad y traición.',
'Serie', 'Publicado', 'Finalizado', 2013, '2013-04-07', 24, 1, 25),

('Fullmetal Alchemist: Brotherhood', 'Hagane no Renkinjutsushi',
'Los hermanos Edward y Alphonse Elric cometen la transmutación humana prohibida para revivir a su madre. El experimento falla: Edward pierde una pierna y Alphonse pierde todo su cuerpo. Edward sacrifica un brazo para sellar el alma de su hermano y busca la Piedra Filosofal para restaurarlos. Una historia magistral sobre el sacrificio y la redención.',
'Serie', 'Publicado', 'Finalizado', 2009, '2009-04-05', 24, 1, 64),

('Death Note', 'Death Note',
'Light Yagami descubre un cuaderno sobrenatural que mata a cualquiera cuyo nombre sea escrito en él. Decide usarlo para eliminar criminales. Sus acciones atraen al mejor detective del mundo, L. Comienza un intenso juego del gato y el ratón entre dos mentes brillantes. Un thriller psicológico sobre la justicia y el poder absoluto.',
'Serie', 'Publicado', 'Finalizado', 2006, '2006-10-04', 23, 1, 37),

('Cowboy Bebop', 'Cowboy Bebop',
'En 2071, Spike Spiegel y Jet Black son cazarrecompensas en la nave Bebop. Se les unen Faye Valentine, Edward y Ein. Juntos enfrentan sus pasados mientras Spike está atrapado entre su vida actual y el sindicato criminal. Con banda sonora de jazz inolvidable, explora la soledad y la redención.',
'Serie', 'Publicado', 'Finalizado', 1998, '1998-10-24', 24, 1, 26),

('Samurai Champloo', 'Samurai Chanpurū',
'Mugen, un espadachín salvaje, y Jin, un samurái estoico, son liberados por Fuu a cambio de que la acompañen a buscar al samurái que huele a girasoles. Viajan por el Japón feudal enfrentando enemigos. Una serie que mezcla samuráis con hip-hop en una combinación única y estilizada.',
'Serie', 'Publicado', 'Finalizado', 2004, '2004-05-20', 24, 1, 26),

('Steins;Gate', 'Steins;Gate',
'Rintaro Okabe y sus amigos descubren que su microondas modificado puede enviar mensajes al pasado. Atraen la atención de una organización misteriosa y alteran la línea temporal. Okabe debe viajar a través de múltiples líneas temporales para salvar a quienes ama. Un thriller de ciencia ficción sobre el costo de jugar con el tiempo.',
'Serie', 'Publicado', 'Finalizado', 2011, '2011-04-06', 24, 1, 24),

('Hunter x Hunter (2011)', 'Hunter x Hunter',
'Gon Freecss descubre que su padre es un Cazador de élite y decide convertirse en uno para encontrarlo. Forma lazos con Killua, Kurapika y Leorio durante el riguroso examen. Una aventura épica sobre la amistad y la superación personal. Considerada una de las mejores series de aventura de todos los tiempos.',
'Serie', 'Publicado', 'Finalizado', 2011, '2011-10-02', 23, 1, 148),

('One Punch Man', 'Wanpanman',
'Saitama se volvió tan poderoso tras su entrenamiento que derrota a cualquier enemigo con un puñetazo, pero está aburrido porque nada es un desafío. Se une a la Asociación de Héroes buscando oponentes dignos. Su discípulo Genos lo acompaña. Una sátira brillante del género de superhéroes con acción espectacular.',
'Serie', 'Publicado', 'Finalizado', 2015, '2015-10-05', 24, 1, 12),

('Mob Psycho 100', 'Mobu Saiko Hundra',
'Shigeo Kageyama, apodado Mob, es uno de los psíquicos más poderosos pero reprime sus emociones para controlar sus poderes. Cuando su medidor emocional llega al 100%, sus poderes se desatan violentamente. Trabaja con Reigen, un estafador, quien le enseña lecciones de vida. Una serie sobre crecimiento personal.',
'Serie', 'Publicado', 'Finalizado', 2016, '2016-07-12', 24, 1, 12),

('Demon Slayer', 'Kimetsu no Yaiba',
'Tanjiro Kamado descubre a su familia asesinada por un demonio y su hermana Nezuko transformada en demonio. Se convierte en cazador de demonios para encontrar una cura. Entrena en la respiración del agua para combatir demonios liderados por Muzan Kibutsuji. Una historia de acción sobre el amor fraternal.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-04-06', 24, 1, 26),

('Jujutsu Kaisen', 'Jūjutsu Kaisen',
'Yuji Itadori abre un sello con el dedo de Ryomen Sukuna y se lo come para salvar a sus amigos. Es reclutado por Satoru Gojo para unirse a la Escuela de Brujería. Debe combatir maldiciones mientras busca los dedos restantes de Sukuna. Acción sobrenatural trepidante con personajes memorables.',
'Serie', 'Publicado', 'Finalizado', 2020, '2020-10-03', 24, 1, 24),

('Tokyo Ghoul', 'Tōkyō Gūru',
'Ken Kaneki se vuelve mitad ghoul tras un trasplante de órganos. Debe sobrevivir en los mundos humano y ghoul mientras acepta su nueva identidad. Se refugia en el café Anteiku mientras es perseguido por la Comisión de Contramedidas Ghoul. Una historia oscura sobre identidad y supervivencia.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-07-04', 24, 1, 12),

('Parasyte', 'Kiseijū: Sei no Kakuritsu',
'Shinichi Izumi es poseído en su brazo por el parásito Migi. Deben coexistir mientras enfrentan a otros parásitos que devoran humanos. La línea entre humano y monstruo se vuelve borrosa. Una serie que explora la humanidad, la evolución y la conciencia.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-10-09', 24, 1, 24),

('Erased', 'Boku dake ga Inai Machi',
'Satoru posee Revival, que lo transporta antes de incidentes fatales. Tras el asesinato de su madre, es enviado a su infancia. Debe descubrir al responsable y evitar tragedias pasadas. Un thriller de viajes en el tiempo lleno de misterio, emoción y giros inesperados.',
'Serie', 'Publicado', 'Finalizado', 2016, '2016-01-08', 24, 1, 12),

('Death Parade', 'Desu Paredo',
'Las almas llegan al bar Quindecim donde Decim las juzga mediante juegos mortales que revelan su naturaleza. Cada episodio presenta nuevas almas enfrentando su juicio mientras Decim intenta comprender las emociones humanas. Explora la moralidad, el arrepentimiento y el significado de estar vivo.',
'Serie', 'Publicado', 'Finalizado', 2015, '2015-01-10', 24, 1, 12),

('Psycho-Pass', 'Saiko Pasu',
'En un futuro distópico, el Sistema Sibyl mide el Psycho-Pass de cada ciudadano. Akane Tsunemori investiga a Shogo Makishima, un criminal inmune al sistema, junto al ejecutor Kogami. Descubren la verdad detrás de Sibyl. Un thriller psicológico sobre la justicia y el libre albedrío.',
'Serie', 'Publicado', 'Finalizado', 2012, '2012-10-12', 24, 1, 22),

('Made in Abyss', 'Meido in Abisu',
'Riko sueña con ser exploradora en el Abismo, un agujero lleno de criaturas extrañas. Conoce a Reg, un robot con forma de niño, y descienden al Abismo para buscar a su madre. Cuanto más profundo bajan, más difícil es regresar. Una aventura oscura y hermosa sobre el descubrimiento y el sacrificio.',
'Serie', 'Publicado', 'Finalizado', 2017, '2017-07-07', 24, 1, 13),

('The Promised Neverland', 'Yakusoku no Nebārando',
'Emma, Norman y Ray descubren que su orfanato es una granja donde crían niños para demonios. Deben escapar antes de ser devorados. Enfrentan a Isabella, una cuidadora brillante y despiadada. Un thriller de supervivencia lleno de tensión, estrategia y giros que mantienen al filo del asiento.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-01-11', 24, 1, 12),

('Vinland Saga', 'Vinland Saga',
'Thorfinn presencia el asesinato de su padre por Askeladd. Se une al grupo para vengarse en duelo justo. Participa en guerras vikingas mientras conoce al rey Canuto. Con el tiempo busca un propósito mayor: encontrar Vinland, una tierra pacífica más allá del océano. Una épica vikinga sobre venganza y redención.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-07-07', 24, 1, 24),

('Banana Fish', 'Banana Fish',
'Ash Lynx investiga las palabras Banana Fish dichas por su hermano antes de morir. Descubre un complot sobre un narcótico y secretos del gobierno. Conoce a Eiji, un fotógrafo japonés que se vuelve su amigo más cercano. Una historia criminal en Nueva York sobre supervivencia, trauma y lealtad.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-07-06', 24, 1, 24),

('91 Days', '91 Days',
'Angelo presencia el asesinato de su familia por la mafia Vanetti. Años después regresa como Avilio Bruno para vengarse. Se acerca a Nero Vanetti mientras planea su venganza en 91 días. Un drama de mafia italoamericana con narrativa tensa y atmósfera oscura.',
'Serie', 'Publicado', 'Finalizado', 2016, '2016-07-09', 24, 1, 12),

('Baccano!', 'Baccano!',
'En 1930, mafiosos, ladrones y alquimistas inmortales chocan en el tren Flying Pussyfoot. La narrativa no lineal entrelaza historias desde 1711 hasta 1930 mostrando cómo las acciones de diferentes personajes están conectadas. Una serie trepidante con elenco coral inolvidable.',
'Serie', 'Publicado', 'Finalizado', 2007, '2007-07-27', 24, 1, 16),

('Durarara!!', 'Durarara!!',
'Mikado se muda a Ikebukuro y descubre un mundo de personajes excéntricos: un informante sádico, un hombre con fuerza sobrehumana y una motociclista sin cabeza. Se involucra en los secretos de la ciudad donde todos están conectados. Una serie sobre rumorología e identidad urbana.',
'Serie', 'Publicado', 'Finalizado', 2010, '2010-01-08', 24, 1, 24),

('Angel Beats!', 'Enjeru Bītsu!',
'Otonashi despierta en un mundo después de la muerte. Conoce a Yuri, líder de la Brigada que lucha contra Dios por destinos injustos. Su enemiga Angel busca eliminarlos. Otonashi ayuda a sus compañeros a encontrar la paz mientras enfrenta su propia transición. Comedia, acción y drama emocional.',
'Serie', 'Publicado', 'Finalizado', 2010, '2010-04-03', 24, 1, 13),

('Clannad', 'Kuranado',
'Tomoya Okazaki, estudiante apático, conoce a Nagisa Furukawa. Juntos reviven el club de teatro reuniendo a compañeros diversos. A través de estas amistades Tomoya encuentra propósito y se enamora. Una conmovedora historia sobre la amistad, la familia y el poder de las conexiones humanas que abarca años.',
'Serie', 'Publicado', 'Finalizado', 2007, '2007-10-04', 24, 1, 23),

('Anohana', 'Ano Hi Mita Hana no Namae o Bokutachi wa Mada Shiranai',
'Jinta vive atormentado por la muerte de su amiga Menma, quien aparece como fantasma. Debe reunir a sus amigos de infancia separados por el trauma. Cada uno carga con su dolor y culpa. Una serie que explora el duelo, la amistad y la dificultad de seguir adelante.',
'Serie', 'Publicado', 'Finalizado', 2011, '2011-04-15', 24, 1, 11),

('March Comes in Like a Lion', '3-gatsu no Lion',
'Rei Kiriyama, prodigio del shogi de 17 años, vive solo y aislado. Su soledad sana al conocer a las hermanas Kawamoto, una familia cálida que lo acepta. A través de ellas y sus rivales, aprende a enfrentar sus demonios. Una serie profundamente humana sobre la depresión y la sanación.',
'Serie', 'Publicado', 'Finalizado', 2016, '2016-10-08', 24, 1, 22),

('Your Lie in April', 'Shigatsu wa Kimi no Uso',
'Kousei, prodigio del piano que dejó de tocar tras la muerte de su madre, conoce a Kaori, una violinista apasionada que lo obliga a tocar con ella. A través de la música redescubre la vida mientras Kaori oculta un secreto devastador. Una historia hermosa y trágica sobre el amor y la música.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-10-09', 24, 1, 22),

('Fruits Basket (2019)', 'Furūtsu Basuketto',
'Tohru descubre el secreto de la familia Sohma: al ser abrazados se transforman en animales del zodiaco. Se convierte en confidente de Yuki, Kyo y los demás, ayudándolos a sanar. Una serie sobre sanación emocional, aceptación y el poder de la bondad.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-04-06', 24, 1, 25),

('Kimi ni Todoke', 'Kimi ni Todoke',
'Sawako es malinterpretada por su aspecto pero es una chica dulce que solo quiere amigos. Kazehaya, el chico más popular, ve más allá de las apariencias y le habla. La ayuda a salir de su caparazón mientras ella desarrolla sentimientos. Una historia entrañable sobre timidez y primer amor.',
'Serie', 'Publicado', 'Finalizado', 2009, '2009-10-06', 24, 1, 25),

('Horimiya', 'Horimiya',
'Kyoko Hori es popular en la escuela pero en casa cuida a su hermano. Izumi Miyamura es callado pero fuera tiene tatuajes. Al encontrarse fuera de la escuela descubren sus verdaderos yo y se vuelven cercanos. Una comedia romántica refrescante sobre la dualidad de las apariencias.',
'Serie', 'Publicado', 'Finalizado', 2021, '2021-01-10', 24, 1, 13),

('My Dress-Up Darling', 'Sono Bisque Doll wa Koi o Suru',
'Wakana Gojo, apasionado por crear muñecas tradicionales, mantiene su afición secreta. Marin, la chica popular, descubre su talento para coser y le pide disfraces de cosplay. Trabajan juntos y nace una amistad que florece en romance. Una serie adorable sobre creatividad y autoexpresión.',
'Serie', 'Publicado', 'Finalizado', 2022, '2022-01-09', 24, 1, 12),

('Komi Can''t Communicate', 'Komi-san wa, Komyushō Desu',
'Shoko Komi, la chica más bella de la escuela, sufre ansiedad social extrema. Tadano descubre su secreto y se vuelve su primer amigo, ayudándola a hacer 100 amigos. Una comedia escolar adorable sobre la superación de la ansiedad y la aceptación de las diferencias.',
'Serie', 'Publicado', 'Finalizado', 2021, '2021-10-07', 24, 1, 12),

('Kaguya-sama: Love Is War', 'Kaguya-sama wa Kokurasetai',
'Miyuki y Kaguya, genios del consejo estudiantil, se admiran pero su orgullo les impide confesar. Cada uno cree que confesar es perder. Comienza una guerra de ingenio donde idean planes para obligar al otro a rendirse. Una comedia romántica brillante sobre el amor y el orgullo.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-01-12', 24, 1, 12),

('Haikyuu!!', 'Haikyū!!',
'Shoyo Hinata, de baja estatura pero gran salto, se apasiona por el voleibol. En Karasuno forma dupla con Kageyama, un genio del voleibol. Juntos llevan a su escuela a la cima superando diferencias. Una serie inspiradora sobre trabajo en equipo, pasión y superación personal.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-04-06', 24, 1, 25),

('Kuroko''s Basketball', 'Kuroko no Basuke',
'La Generación de los Milagros tuvo un sexto jugador invisible: Kuroko. Ahora forma dupla con Kagami en Seirin para derrotar a cada prodigio. Una serie deportiva llena de acción que eleva el básquetbol a niveles fantásticos mientras los jugadores superan sus límites.',
'Serie', 'Publicado', 'Finalizado', 2012, '2012-04-08', 24, 1, 25),

('Hajime no Ippo', 'Hajime no Ippo',
'Ippo, estudiante tímido acosado, descubre talento para el boxeo en el gimnasio Kamogawa. A través de cada pelea aprende técnica, estrategia y coraje. La serie muestra su crecimiento como boxeador y persona mientras forma lazos con sus compañeros. Un clásico del deporte.',
'Serie', 'Publicado', 'Finalizado', 2000, '2000-10-04', 24, 1, 75),

('Megalo Box', 'Megaro Bokusu',
'Junk Dog, luchador callejero de Megalo Box, es humillado por el campeón Yuuri. Adopta la identidad de Joe y participa en Megalonia sin Gear avanzado. Un homenaje al clásico Ashita no Joe con estética retro-futurista y banda sonora excepcional.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-04-06', 24, 1, 13),

('Run with the Wind', 'Kaze ga Tsuyoku Fuiteiru',
'Kakeru es reclutado por Haiji para vivir en Chikusei-so y formar un equipo para el Hakone Ekiden. Los diez residentes excéntricos se entrenan juntos superando lesiones y conflictos. Una inspiradora historia sobre trabajo en equipo, perseverancia y amistad.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-10-03', 24, 1, 23),

('Free!', 'Free!',
'Haruka, apasionado por la natación, se reencuentra con sus amigos de la infancia. Rin ahora lo ve como rival. Forman el club de natación de Iwatobi para competir. Una serie sobre la amistad, la pasión por el deporte y superar diferencias personales a través de la natación.',
'Serie', 'Publicado', 'Finalizado', 2013, '2013-07-04', 24, 1, 12),

('Yuri on Ice', 'Yuri on Ice',
'Yuri Katsuki, patinador artístico, regresa a casa tras derrota considerando retirarse. Viktor Nikiforov, su ídolo, aparece y se ofrece como entrenador. Yuri regresa a la competencia enfrentando rivales internacionales. En el hielo encuentra su pasión y un amor inesperado.',
'Serie', 'Publicado', 'Finalizado', 2016, '2016-10-06', 24, 1, 12),

('Food Wars!', 'Shokugeki no Sōma',
'Soma es enviado a la Academia Totsuki donde debe enfrentar batallas culinarias llamadas Shokugeki. Con creatividad y personalidad competitiva, desafía a estudiantes prodigio. Una serie que combina cocina, comedia y competencia con platos espectaculares.',
'Serie', 'Publicado', 'Finalizado', 2015, '2015-04-04', 24, 1, 24),

('Dr. Stone', 'Dokutā Sutōn',
'Senku despierta miles de años después de que un rayo petrificara a la humanidad. Planea reconstruir la civilización desde cero usando ciencia. Pero Tsukasa tiene una visión diferente. Una fascinante aventura sobre el poder del conocimiento humano para superar cualquier obstáculo.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-07-05', 24, 1, 24),

('That Time I Got Reincarnated as a Slime', 'Tensei Shitara Slime Datta Ken',
'Satoru se reencarna como un limo llamado Rimuru en un mundo de fantasía. Adquiere habilidades únicas y construye una nación de monstruos donde todas las razas conviven. Una serie isekai que combina construcción de mundos, política y batallas épicas.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-10-02', 24, 1, 24),

('Re:Zero', 'Re:Zero kara Hajimeru Isekai Seikatsu',
'Subaru es invocado a un mundo de fantasía sin poderes. Al morir regresa al tiempo, reviviendo eventos. Cada muerte es dolorosa. Se enamora de Emilia mientras enfrenta la desesperación de revivir la muerte una y otra vez. Un isekai psicológico sobre resiliencia y sacrificio.',
'Serie', 'Publicado', 'Finalizado', 2016, '2016-04-04', 24, 1, 25),

('Overlord', 'Ōbārōdo',
'Momonga queda atrapado en YGGDRASIL cuando cierran servidores. En forma de esqueleto imparable, adopta el nombre Ainz Ooal Gown y conquista el nuevo mundo con sus sirvientes. Busca descubrir si otros jugadores también fueron transportados.',
'Serie', 'Publicado', 'Finalizado', 2015, '2015-07-07', 24, 1, 13),

('Konosuba', 'Kono Subarashii Sekai ni Shukufuku o!',
'Kazuma muere ridículamente y se lleva a la diosa Aqua a un mundo de fantasía. Forman grupo con Megumin, que lanza un solo hechizo al día, y Darkness, caballero masoquista. Una comedia de parodia del género isekai con personajes inolvidables.',
'Serie', 'Publicado', 'Finalizado', 2016, '2016-01-14', 24, 1, 10),

('No Game No Life', 'Nō Gēmu Nō Raifu',
'Sora y Shiro, hermanos invictos en juegos, son transportados a Disboard donde todo se resuelve mediante juegos. Desafían a las razas gobernantes empezando por los Imanity. Una serie visualmente deslumbrante llena de estrategia y comedia.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-04-09', 24, 1, 12),

('Sword Art Online', 'Sōdo Āto Onrain',
'Diez mil jugadores quedan atrapados en SAO. Kirito lucha por sobrevivir y proteger a otros mientras conoce a Asuna. Juntos enfrentan los peligros de Aincrad. Una serie de acción y romance en un mundo virtual donde morir significa morir de verdad.',
'Serie', 'Publicado', 'Finalizado', 2012, '2012-07-07', 24, 1, 25),

('Log Horizon', 'Log Horizon',
'30,000 jugadores quedan atrapados en Elder Tales. Shiroe, estratega experto, establece leyes, economía y gobierno en Akiba. La serie se enfoca en política y construcción de sociedad dentro del juego. Un isekai que prioriza la estrategia sobre la acción.',
'Serie', 'Publicado', 'Finalizado', 2013, '2013-10-05', 24, 1, 25),

('The Rising of the Shield Hero', 'Tate no Yūsha no Nariagari',
'Naofumi es invocado como el Héroe del Escudo, el más débil. Traicionado por su reino, compra a Raphtalia y Filo. Juntos superan la adversidad mientras demuestra que el escudo es el arma más poderosa. Una historia de superación, redención y venganza.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-01-09', 24, 1, 25),

('Mushoku Tensei', 'Mushoku Tensei: Isekai Ittara Honki Dasu',
'Un fracasado social se reencarna como Rudeus Greyrat. Conserva sus recuerdos y decide no repetir errores. Aprende magia y espada desde niño. La serie sigue su vida desde la infancia hasta la adultez, explorando su crecimiento personal y relaciones.',
'Serie', 'Publicado', 'Finalizado', 2021, '2021-01-11', 24, 1, 23),

('Neon Genesis Evangelion', 'Shin Seiki Evangerion',
'Shinji, niño de 14 años, es reclutado por su padre para pilotar el Evangelion contra Ángeles. Junto a Rei y Asuka lucha mientras enfrenta inseguridades y traumas. Una serie psicológica que subvierte el género mecha, explorando depresión e identidad.',
'Serie', 'Publicado', 'Finalizado', 1995, '1995-10-04', 24, 1, 26),

('Gurren Lagann', 'Tengen Toppa Guren Ragan',
'Simon encuentra una broca y el robot Lagann. Con Kamina rompe el techo de su aldea y descubre un mundo oprimido. Forman el Equipo Gurren para liberar a la humanidad. Una serie épica sobre perseverancia y voluntad humana con animación espectacular.',
'Serie', 'Publicado', 'Finalizado', 2007, '2007-04-01', 24, 1, 27),

('Eureka Seven', 'Eureka Sebun',
'Renton sueña con pilotar un LFO. Cuando el Nirvash se estrella en su casa, se une a Gekkostate. Aprende a pilotar y se enamora de Eureka mientras descubre la verdad sobre el mundo. Una serie de mechas con estética surf y banda sonora excepcional.',
'Serie', 'Publicado', 'Finalizado', 2005, '2005-04-17', 24, 1, 50),

('Code Geass', 'Code Geass: Hangyaku no Lelouch',
'Lelouch recibe el poder Geass y se convierte en Zero para liderar la resistencia contra Britannia. Su mejor amigo Suzaku se vuelve su enemigo. Una serie de mechas y estrategia política con giros impactantes y uno de los finales más memorables del anime.',
'Serie', 'Publicado', 'Finalizado', 2006, '2006-10-06', 24, 1, 25),

('Full Metal Panic!', 'Furu Metaru Panikku!',
'Sousuke, sargento de Mithril, protege a Kaname infiltrándose en su escuela. Su mentalidad militar crea situaciones cómicas mientras la defiende de terroristas. Una mezcla de mechas, acción y comedia romántica escolar.',
'Serie', 'Publicado', 'Finalizado', 2002, '2002-01-15', 24, 1, 24),

('Suisei no Gargantia', 'Gargantia on the Verdurous Planet',
'Ledo, piloto de mechas, cae a la Tierra. Es rescatado por Gargantia, una flota de barcos. Sin habilidades para la vida cotidiana, aprende a adaptarse a la vida pacífica con Amy. Una serie sobre el contraste entre guerra y civilización.',
'Serie', 'Publicado', 'Finalizado', 2013, '2013-04-07', 24, 1, 13),

('Darling in the Franxx', 'Dārin za Furankusu',
'Hiro pierde capacidad de pilotear hasta conocer a Zero Two, mitad humana. Ella lo elige como pareja y descubren una conexión única que revela secretos de su existencia. Una serie de mechas con temática romántica sobre identidad y libertad.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-01-13', 24, 1, 24),

('Aldnoah.Zero', 'Arudonoa Zero',
'Los colonos marcianos de Vers declaran guerra a la Tierra usando tecnología Aldnoah. Inaho, estudiante estratega, combate poderosos mechas marcianos con su ingenio. Una serie de ciencia ficción militar con intensas batallas y giros políticos.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-07-06', 24, 1, 12),

('Guilty Crown', 'Giruti Kuraun',
'Shu recibe el Void Genome de Inori, miembro de Funeral Parlor. Puede extraer objetos del interior de personas que representan su personalidad. Se une a la lucha contra el GHQ. Una serie de ciencia ficción sobre el poder, el sacrificio y la responsabilidad.',
'Serie', 'Publicado', 'Finalizado', 2011, '2011-10-14', 24, 1, 22),

('Akame ga Kill!', 'Akame ga Kiru!',
'Tatsumi descubre la corrupción del Imperio y es reclutado por Night Raid, asesinos con armas legendarias. Debe adaptarse mientras forja lazos con sus compañeros. Una serie oscura donde nadie está a salvo y el costo de la revolución es alto.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-07-06', 24, 1, 24),

('Fate/Stay Night: Unlimited Blade Works', 'Fate/Stay Night',
'Shirou se ve envuelto en la Guerra del Santo Grial. Convoca a Saber y debe sobrevivir contra otros magos mientras descubre secretos del Grial. Una serie visualmente impresionante con coreografías de lucha excepcionales y exploración de ideales heroicos.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-10-04', 24, 1, 25),

('Akudama Drive', 'Akudama Doraibu',
'Una estudiante se hace pasar por criminal para unirse a los Akudama en una misión. Junto al Ejecutor y el Doctor, enfrentan a la policía de élite. Una serie cyberpunk con estilo vibrante que combina caos, violencia y humanidad.',
'Serie', 'Publicado', 'Finalizado', 2020, '2020-10-08', 24, 1, 12),

('Terror in Resonance', 'Zankyō no Terror',
'Nine y Twelve toman Tokio con ataques terroristas publicando acertijos. Detrás hay un experimento gubernamental que los usó de niños. Con Lisa, ejecutan su plan de venganza mientras la policía se acerca. Un thriller con banda sonora de Yoko Kanno.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-07-11', 24, 1, 11),

('Devilman Crybaby', 'Debiruman Kuraibēbī',
'Akira se fusiona con Amon para ser Devilman, con poderes demoníacos pero corazón humano. La sociedad se desintegra en paranoia mientras lucha contra demonios. Una serie brutal sobre el lado más oscuro de humanos y demonios.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-01-05', 24, 1, 10),

('The Great Pretender', 'Gurēto Puritendā',
'Makoto es estafado por Laurent y se une a su banda internacional. Ejecutan timos contra corruptos poderosos alrededor del mundo. Una serie elegante con diseño vibrante y jazz sobre el engaño, la redención y la amistad.',
'Serie', 'Publicado', 'Finalizado', 2020, '2020-06-02', 24, 1, 23),

('Dororo', 'Dororo',
'Hyakkimaru, a quien su padre ofreció a 48 demonios, viaja matándolos para recuperar su cuerpo. Acompañado por Dororo, un niño huérfano, enfrenta demonios y humanos corruptos. Una historia de samuráis sobre identidad y el costo del poder.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-01-09', 24, 1, 24),

('Carole & Tuesday', 'Kyaroru Ando Chūzudei',
'Dos chicas opuestas forman un dúo musical en Marte desafiando la industria dominada por IA. Su música hecha con emociones reales toca corazones. Una serie sobre el poder de la música, los sueños y la conexión humana.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-04-11', 24, 1, 24),

('Beastars', 'Bīsutāzu',
'Legoshi, lobo grande y tímido, lucha con instintos depredadores. Conoce a Haru, una coneja que despierta sentimientos confusos. Un asesinato aumenta tensiones entre especies. Una serie que explora identidad, prejuicio y deseo.',
'Serie', 'Publicado', 'Finalizado', 2019, '2019-10-10', 24, 1, 12),

('Violet Evergarden', 'Violet Evergarden',
'Violet, criada como arma sin emociones, queda discapacitada tras la guerra. Trabaja como Auto Memory Doll escribiendo cartas. A través de este trabajo comprende emociones mientras busca entender las últimas palabras de su comandante: te quiero.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-01-11', 24, 1, 13),

('Monster', 'Monster',
'El Dr. Tenma salva a Johan, que años después se vuelve un asesino despiadado. Acusado injustamente, Tenma debe huir y perseguirlo descubriendo oscuros experimentos. Un thriller psicológico sobre la naturaleza del mal y el valor de la vida.',
'Serie', 'Publicado', 'Finalizado', 2004, '2004-04-07', 24, 1, 74),

('A Place Further Than the Universe', 'Sora yori mo Tōi Basho',
'Mari quiere hacer algo emocionante antes de graduarse. Con Shirase viajan a la Antártida para encontrar a su madre desaparecida. Una inspiradora historia sobre determinación juvenil y la belleza de perseguir sueños imposibles.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-01-02', 24, 1, 13),

('Hyouka', 'Hyouka',
'Houtarou, estudiante apático, es arrastrado al Club de Literatura por la curiosa Chitanda. Usa su deducción para resolver misterios cotidianos que esconden secretos profundos. Una serie slow-burn sobre misterio y romance sutil.',
'Serie', 'Publicado', 'Finalizado', 2012, '2012-04-23', 24, 1, 22),

('Wotakoi: Love is Hard for Otaku', 'Wotaku ni Koi wa Muzukashii',
'Narumi, oficinista otaku, se reencuentra con Hirotaka, gamer de infancia. Tras ser rechazada, él le propone salir. Navegan ser adultos otaku en el mundo corporativo. Una comedia romántica para adultos que celebra la cultura otaku.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-04-13', 24, 1, 11),

('Daily Lives of High School Boys', 'Danshi Kōkōsei no Nichijō',
'Tadakuni, Hidenori y Yoshitake viven situaciones absurdas y cómicas. Desde batallas de espadas de palo hasta discusiones sobre cupones. Una comedia pura que celebra la amistad masculina adolescente y la tontería de la juventud.',
'Serie', 'Publicado', 'Finalizado', 2012, '2012-01-10', 24, 1, 12),

('Grand Blue', 'Grand Blue',
'Iori comienza la universidad en la tienda Grand Blue. Su sueño de vida ideal se desvanece ante el club de buceo obsesionado con beber. Una comedia de humor absurdo sobre la vida universitaria y la amistad.',
'Serie', 'Publicado', 'Finalizado', 2018, '2018-07-14', 24, 1, 12),

('Barakamon', 'Barakamon',
'Seishuu, calígrafo profesional, es enviado a una isla remota. Los lugareños y la niña Naru lo ayudan a encontrar inspiración. Una serie cálida sobre crecimiento personal, comunidad y autenticidad artística.',
'Serie', 'Publicado', 'Finalizado', 2014, '2014-07-06', 24, 1, 12),

('Usagi Drop', 'Usagi Doroppu',
'Daikichi toma la custodia de Rin, de 6 años. Sin experiencia con niños, aprende a ser padre. Una historia simple pero conmovedora sobre paternidad, responsabilidad y amor incondicional.',
'Serie', 'Publicado', 'Finalizado', 2011, '2011-07-08', 24, 1, 11),

('Natsume''s Book of Friends', 'Natsume Yūjin-Chō',
'Natsume hereda el Libro de los Amigos con nombres de yokai. Decide devolverlos con Madara, un gato guardaespaldas. Una serie calmada sobre la soledad y la conexión entre humanos y espíritus.',
'Serie', 'Publicado', 'Finalizado', 2008, '2008-07-07', 24, 1, 13),

('Flying Witch', 'Flying Witch',
'Makoto, bruja de 15 años, se muda a Aomori para entrenar. Vive con sus primos usando magia sutil para lo cotidiano. Una serie relajada que captura la belleza de los pequeños momentos. Un slice of life mágico sobre armonía entre magia y naturaleza.',
'Serie', 'Publicado', 'Finalizado', 2016, '2016-04-10', 24, 1, 12),

('Non Non Biyori', 'Non Non Biyori',
'La familia Ichijo se muda al remoto pueblo Asahigaoka. Hotaru se adapta a la vida rural con Renge, Natsumi y Komari. Cada episodio captura la simplicidad de la vida en el campo. Un remedio contra el estrés que celebra la amistad infantil y la naturaleza.',
'Serie', 'Publicado', 'Finalizado', 2013, '2013-10-07', 24, 1, 12);

-- ============================================================
-- ASIGNACIONES DE GÉNEROS
-- ============================================================
-- Contenido inicial
INSERT INTO peliculas_series_generos (id_pelicula_serie, id_genero) VALUES
(1,5),(1,6),(1,4),
(2,3),(2,5),(2,4),
(3,3),(3,11),(3,4);

-- Las películas comienzan desde ID 4 (existen IDs 1,2,3)
-- ID 4: Akira - Acción(1), Ciencia Ficción(7), Psicología(16)
INSERT INTO peliculas_series_generos (id_pelicula_serie, id_genero) VALUES
(4,1),(4,7),(4,16),
(5,1),(5,7),(5,17),
(6,2),(6,6),
(7,2),(7,6),
(8,2),(8,4),(8,6),
(9,6),(9,12),
(10,4),(10,5),(10,12),
(11,2),(11,6),(11,7),
(12,2),(12,6),(12,7),
(13,6),(13,12),
(14,16),(14,9),(14,10),
(15,6),(15,7),(15,16),
(16,3),(16,5),(16,7),
(17,2),(17,5),(17,6),
(18,12),(18,5),(18,6),
(19,4),(19,7),(19,12),
(20,2),(20,5),(20,6),
(21,5),(21,4),(21,12),
(22,5),(22,10),(22,11),
(23,4),(23,5),(23,6),
(24,2),(24,6),(24,4),
(25,5),(25,6),(25,2),
(26,5),(26,12),
(27,5),(27,4),(27,11),
(28,5),(28,4),(28,6),
(29,3),(29,5),(29,2),
(30,7),(30,2),(30,4),
(31,6),(31,12),
(32,4),(32,12),(32,5),
(33,9),(33,5),(33,6),
(34,6),(34,5),
(35,12),(35,5),(35,4),
(36,4),(36,12),(36,5),
(37,6),(37,2),(37,3),
(38,6),(38,2),
(39,4),(39,5),(39,11),
(40,6),(40,2),(40,5),
(41,1),(41,5),(41,16),
(42,1),(42,7),(42,2),
(43,1),(43,7),
(44,5),(44,7),(44,2),
(45,1),(45,2),(45,5),
(46,1),(46,2),(46,5),
(47,6),(47,1),(47,10),
(48,1),(48,2),(48,6),
(49,1),(49,7),(49,17),
(50,1),(50,7);

-- Series desde ID 54 hasta ~135
-- 54: Attack on Titan - Acción(1), Drama(5), Fantasía(6)
INSERT INTO peliculas_series_generos (id_pelicula_serie, id_genero) VALUES
(54,1),(54,5),(54,6),
(55,1),(55,2),(55,6),(55,5),
(56,9),(56,16),(56,8),
(57,1),(57,7),(57,3),
(58,1),(58,2),(58,3),
(59,7),(59,17),(59,16),
(60,1),(60,2),(60,6),
(61,1),(61,3),(61,7),
(62,8),(62,3),(62,1),
(63,1),(63,6),
(64,1),(64,8),
(65,10),(65,16),(65,8),
(66,10),(66,7),(66,5),
(67,9),(67,17),(67,8),
(68,16),(68,5),(68,8),
(69,7),(69,17),(69,16),
(70,6),(70,2),(70,5),
(71,10),(71,9),(71,16),
(72,1),(72,2),(72,5),
(73,1),(73,5),(73,17),
(74,5),(74,17),(74,1),
(75,1),(75,3),(75,9),
(76,1),(76,9),(76,8),
(77,3),(77,5),(77,8),
(78,4),(78,5),(78,12),(78,11),
(79,5),(79,8),(79,12),
(80,5),(80,12),
(81,4),(81,5),(81,11),
(82,4),(82,3),(82,6),(82,8),
(83,4),(83,3),(83,11),(83,12),
(84,4),(84,3),(84,11),
(85,4),(85,3),(85,12),(85,11),
(86,3),(86,12),(86,11),
(87,3),(87,4),(87,11),
(88,13),(88,3),(88,11),
(89,13),(89,11),
(90,13),(90,1),(90,3),
(91,13),(91,1),(91,5),
(92,13),(92,12),(92,5),
(93,13),(93,12),(93,11),
(94,13),(94,4),(94,5),
(95,3),(95,11),
(96,7),(96,2),(96,3),
(97,14),(97,6),(97,3),
(98,14),(98,16),(98,5),(98,6),
(99,14),(99,6),(99,1),
(100,14),(100,3),(100,6),
(101,14),(101,3),(101,6),
(102,14),(102,1),(102,4),(102,6),
(103,14),(103,6),(103,3),
(104,14),(104,6),(104,3),
(105,14),(105,1),(105,6),(105,5),
(106,14),(106,6),(106,3),
(107,15),(107,16),(107,7),(107,5),
(108,15),(108,1),(108,3),(108,7),
(109,15),(109,7),(109,4),(109,2),
(110,15),(110,1),(110,16),(110,7),
(111,15),(111,1),(111,3),
(112,15),(112,7),(112,2),
(113,15),(113,4),(113,7),(113,5),
(114,15),(114,1),(114,7),(114,5),
(115,1),(115,7),(115,5),
(116,1),(116,5),
(117,1),(117,6),(117,7),
(118,7),(118,17),
(119,1),(119,8),(119,5),
(120,1),(120,2),(120,6),(120,8),
(121,1),(121,3),
(122,1),(122,3),
(123,3),(123,12),(123,5),
(124,3),(124,4),(124,12),
(51,1),(51,2),(51,6),
(52,1),(52,2),(52,7),
(53,1),(53,7),(53,15),
(125,5),(125,9),(125,16),(125,17),
(126,2),(126,5),(126,12),
(127,9),(127,11),(127,12),
(128,3),(128,4),(128,12),
(129,3),(129,11),(129,12),
(130,3),(130,12),(130,13),
(131,3),(131,12),
(132,5),(132,12),
(133,5),(133,8),(133,12),
(134,3),(134,6),(134,12),
(135,3),(135,12);

-- ============================================================
-- TEMPORADAS ADICIONALES SIN PORTADA, BANNER NI TRAILER
-- ============================================================
DELIMITER $$
DROP PROCEDURE IF EXISTS sp_seed_temporada_contenido $$
CREATE PROCEDURE sp_seed_temporada_contenido(
    IN p_padre VARCHAR(150),
    IN p_titulo VARCHAR(150),
    IN p_titulo_original VARCHAR(150),
    IN p_descripcion TEXT,
    IN p_anio INT,
    IN p_fecha DATE,
    IN p_duracion INT,
    IN p_episodios INT,
    IN p_numero INT
)
BEGIN
    DECLARE v_padre_id INT DEFAULT 0;
    DECLARE v_generos TEXT DEFAULT '';

    SELECT COALESCE((
        SELECT id_pelicula_serie
        FROM peliculas_series
        WHERE titulo = p_padre
        LIMIT 1
    ), 0) INTO v_padre_id;

    IF v_padre_id > 0 THEN
        SELECT COALESCE(GROUP_CONCAT(id_genero ORDER BY id_genero SEPARATOR ','), '')
        INTO v_generos
        FROM peliculas_series_generos
        WHERE id_pelicula_serie = v_padre_id;

        IF NOT EXISTS (
            SELECT 1
            FROM peliculas_series
            WHERE serie_padre_id = v_padre_id
            AND numero_temporada = p_numero
            AND tipo_relacion = 'temporada'
            LIMIT 1
        ) THEN
            CALL sp_crear_pelicula_serie(
                p_titulo,
                p_titulo_original,
                p_descripcion,
                'Serie',
                'Publicado',
                'Finalizado',
                p_anio,
                p_fecha,
                p_duracion,
                1,
                p_episodios,
                NULL,
                NULL,
                NULL,
                v_generos,
                0,
                v_padre_id,
                p_numero,
                'temporada'
            );
        END IF;
    END IF;
END $$
DELIMITER ;

CALL sp_seed_temporada_contenido('Attack on Titan Temp 1', 'Attack on Titan Temp 2', 'Shingeki no Kyojin Season 2', 'Segunda temporada de Attack on Titan. Eren y el Cuerpo de Exploración enfrentan nuevas amenazas mientras se revelan secretos sobre los titanes dentro de las murallas.', 2017, '2017-04-01', 24, 12, 2);
CALL sp_seed_temporada_contenido('Attack on Titan Temp 1', 'Attack on Titan Temp 3', 'Shingeki no Kyojin Season 3', 'Tercera temporada de Attack on Titan. La historia profundiza en los secretos del gobierno, la familia Reiss y el verdadero origen del poder de los titanes.', 2018, '2018-07-23', 24, 12, 3);
CALL sp_seed_temporada_contenido('Attack on Titan Temp 1', 'Attack on Titan Temp 3 Parte 2', 'Shingeki no Kyojin Season 3 Part 2', 'Continuación de la tercera temporada. La batalla por Shiganshina revela verdades decisivas sobre el mundo exterior y cambia el destino de la humanidad.', 2019, '2019-04-29', 24, 10, 4);
CALL sp_seed_temporada_contenido('Attack on Titan Temp 1', 'Attack on Titan Final Season', 'Shingeki no Kyojin: The Final Season', 'Temporada final de Attack on Titan. El conflicto se expande más allá de Paradis y muestra la guerra desde el lado de Marley.', 2020, '2020-12-07', 24, 16, 5);
CALL sp_seed_temporada_contenido('Attack on Titan Temp 1', 'Attack on Titan Final Season Parte 2', 'Shingeki no Kyojin: The Final Season Part 2', 'Segunda parte de la temporada final. Eren avanza con un plan extremo mientras sus antiguos aliados deben decidir cómo responder.', 2022, '2022-01-10', 24, 12, 6);
CALL sp_seed_temporada_contenido('Steins;Gate', 'Steins;Gate 0', 'Steins;Gate 0', 'Continuación alternativa de Steins;Gate. Okabe vive en una línea temporal marcada por la pérdida y vuelve a quedar atrapado entre ciencia, culpa y destino.', 2018, '2018-04-12', 24, 23, 2);
CALL sp_seed_temporada_contenido('One Punch Man', 'One Punch Man Temp 2', 'One Punch Man Season 2', 'Segunda temporada de One Punch Man. Saitama continúa buscando un rival digno mientras Garou desafía a la Asociación de Héroes.', 2019, '2019-04-10', 24, 12, 2);
CALL sp_seed_temporada_contenido('One Punch Man', 'One Punch Man Temp 3', 'One Punch Man Season 3', 'Tercera temporada de One Punch Man. El conflicto contra la Asociación de Monstruos escala y los héroes deben enfrentar amenazas cada vez más peligrosas.', 2025, '2025-10-05', 24, 12, 3);
CALL sp_seed_temporada_contenido('Mob Psycho 100', 'Mob Psycho 100 II', 'Mob Psycho 100 II', 'Segunda temporada de Mob Psycho 100. Mob madura emocionalmente mientras sus poderes y sus relaciones personales lo ponen frente a nuevos dilemas.', 2019, '2019-01-07', 24, 13, 2);
CALL sp_seed_temporada_contenido('Mob Psycho 100', 'Mob Psycho 100 III', 'Mob Psycho 100 III', 'Tercera temporada de Mob Psycho 100. Mob enfrenta cambios internos, decisiones sobre su futuro y el peso de sus emociones acumuladas.', 2022, '2022-10-06', 24, 12, 3);
CALL sp_seed_temporada_contenido('Demon Slayer', 'Demon Slayer: Mugen Train Arc', 'Kimetsu no Yaiba: Mugen Ressha-hen', 'Arco del Tren Infinito en versión serie. Tanjiro, Nezuko, Zenitsu e Inosuke acompañan a Rengoku en una misión marcada por demonios y sueños peligrosos.', 2021, '2021-10-10', 24, 7, 2);
CALL sp_seed_temporada_contenido('Demon Slayer', 'Demon Slayer: Entertainment District Arc', 'Kimetsu no Yaiba: Yuukaku-hen', 'Arco del Distrito Rojo. Tanjiro y sus compañeros se unen al Pilar del Sonido para investigar desapariciones causadas por demonios.', 2021, '2021-12-05', 24, 11, 3);
CALL sp_seed_temporada_contenido('Demon Slayer', 'Demon Slayer: Swordsmith Village Arc', 'Kimetsu no Yaiba: Katanakaji no Sato-hen', 'Arco de la Aldea de los Herreros. Tanjiro viaja a reparar su espada y se enfrenta a poderosos demonios de las Lunas Superiores.', 2023, '2023-04-09', 24, 11, 4);
CALL sp_seed_temporada_contenido('Demon Slayer', 'Demon Slayer: Hashira Training Arc', 'Kimetsu no Yaiba: Hashira Geiko-hen', 'Arco del Entrenamiento de los Pilares. Los cazadores se preparan para la confrontación decisiva contra Muzan y sus demonios.', 2024, '2024-05-12', 24, 8, 5);
CALL sp_seed_temporada_contenido('Jujutsu Kaisen', 'Jujutsu Kaisen Temp 2', 'Jujutsu Kaisen Season 2', 'Segunda temporada de Jujutsu Kaisen. Presenta el pasado de Gojo y Geto y desarrolla el devastador Incidente de Shibuya.', 2023, '2023-07-06', 24, 23, 2);
CALL sp_seed_temporada_contenido('Jujutsu Kaisen', 'Jujutsu Kaisen Temp 3', 'Jujutsu Kaisen Season 3', 'Tercera temporada de Jujutsu Kaisen. La historia entra en el arco del Culling Game con nuevos hechiceros, reglas mortales y consecuencias de Shibuya.', 2026, '2026-01-08', 24, 12, 3);
CALL sp_seed_temporada_contenido('Tokyo Ghoul', 'Tokyo Ghoul Root A', 'Tokyo Ghoul √A', 'Segunda temporada de Tokyo Ghoul. Kaneki toma un camino distinto al unirse a Aogiri y se hunde más en el mundo de los ghouls.', 2015, '2015-01-09', 24, 12, 2);
CALL sp_seed_temporada_contenido('Tokyo Ghoul', 'Tokyo Ghoul:re', 'Tokyo Ghoul:re', 'Continuación de Tokyo Ghoul. Haise Sasaki lidera un escuadrón especial mientras fragmentos de su pasado comienzan a despertar.', 2018, '2018-04-03', 24, 12, 3);
CALL sp_seed_temporada_contenido('Tokyo Ghoul', 'Tokyo Ghoul:re Temp 2', 'Tokyo Ghoul:re 2nd Season', 'Segunda parte de Tokyo Ghoul:re. La guerra entre humanos y ghouls escala mientras Kaneki recupera su identidad y enfrenta decisiones imposibles.', 2018, '2018-10-09', 24, 12, 4);
CALL sp_seed_temporada_contenido('Psycho-Pass', 'Psycho-Pass 2', 'Psycho-Pass 2', 'Segunda temporada de Psycho-Pass. Akane enfrenta un nuevo caso que pone en duda los límites y contradicciones del Sistema Sibyl.', 2014, '2014-10-10', 24, 11, 2);
CALL sp_seed_temporada_contenido('Psycho-Pass', 'Psycho-Pass 3', 'Psycho-Pass 3', 'Tercera temporada de Psycho-Pass. Nuevos inspectores investigan casos complejos mientras surgen conspiraciones dentro de una sociedad vigilada.', 2019, '2019-10-24', 45, 8, 3);
CALL sp_seed_temporada_contenido('Made in Abyss', 'Made in Abyss: The Golden City of the Scorching Sun', 'Made in Abyss: Retsujitsu no Ougonkyou', 'Segunda temporada de Made in Abyss. Riko, Reg y Nanachi descienden hacia una capa más peligrosa del Abismo y descubren una aldea llena de secretos.', 2022, '2022-07-06', 24, 12, 2);
CALL sp_seed_temporada_contenido('The Promised Neverland', 'The Promised Neverland Temp 2', 'Yakusoku no Neverland 2nd Season', 'Segunda temporada de The Promised Neverland. Emma y los demás intentan sobrevivir fuera del orfanato en un mundo dominado por demonios.', 2021, '2021-01-08', 24, 11, 2);
CALL sp_seed_temporada_contenido('Vinland Saga', 'Vinland Saga Temp 2', 'Vinland Saga Season 2', 'Segunda temporada de Vinland Saga. Thorfinn enfrenta las consecuencias de su vida de violencia y busca un nuevo sentido en una etapa marcada por la esclavitud y la redención.', 2023, '2023-01-10', 24, 24, 2);
CALL sp_seed_temporada_contenido('Durarara!!', 'Durarara!!x2 Shou', 'Durarara!!x2 Shou', 'Continuación de Durarara!!. Ikebukuro vuelve a llenarse de rumores, pandillas y conflictos alrededor de sus personajes conectados.', 2015, '2015-01-10', 24, 12, 2);
CALL sp_seed_temporada_contenido('Durarara!!', 'Durarara!!x2 Ten', 'Durarara!!x2 Ten', 'Segunda parte de Durarara!!x2. Las tensiones en Ikebukuro crecen y las relaciones entre sus facciones se vuelven más peligrosas.', 2015, '2015-07-04', 24, 12, 3);
CALL sp_seed_temporada_contenido('Durarara!!', 'Durarara!!x2 Ketsu', 'Durarara!!x2 Ketsu', 'Parte final de Durarara!!x2. Las historias cruzadas de Ikebukuro llegan a su clímax con alianzas, secretos y enfrentamientos.', 2016, '2016-01-09', 24, 12, 4);
CALL sp_seed_temporada_contenido('Clannad', 'Clannad: After Story', 'Clannad: After Story', 'Continuación de Clannad. Tomoya y Nagisa avanzan hacia la vida adulta enfrentando familia, amor, pérdida y esperanza.', 2008, '2008-10-03', 24, 24, 2);
CALL sp_seed_temporada_contenido('March Comes in Like a Lion', 'March Comes in Like a Lion Temp 2', '3-gatsu no Lion 2nd Season', 'Segunda temporada de March Comes in Like a Lion. Rei continúa creciendo en el shogi y en su vida emocional junto a las hermanas Kawamoto.', 2017, '2017-10-14', 24, 22, 2);
CALL sp_seed_temporada_contenido('Fruits Basket (2019)', 'Fruits Basket Temp 2', 'Fruits Basket 2nd Season', 'Segunda temporada de Fruits Basket. Tohru conoce más profundamente las heridas de la familia Sohma y el peso de la maldición del zodiaco.', 2020, '2020-04-07', 24, 25, 2);
CALL sp_seed_temporada_contenido('Fruits Basket (2019)', 'Fruits Basket Final Season', 'Fruits Basket: The Final', 'Temporada final de Fruits Basket. La maldición de los Sohma se acerca a su resolución y cada personaje enfrenta su propio cierre emocional.', 2021, '2021-04-06', 24, 13, 3);
CALL sp_seed_temporada_contenido('Kimi ni Todoke', 'Kimi ni Todoke Temp 2', 'Kimi ni Todoke 2nd Season', 'Segunda temporada de Kimi ni Todoke. Sawako y Kazehaya intentan expresar lo que sienten mientras los malentendidos y la inseguridad complican su relación.', 2011, '2011-01-12', 24, 12, 2);
CALL sp_seed_temporada_contenido('Kimi ni Todoke', 'Kimi ni Todoke Temp 3', 'Kimi ni Todoke 3rd Season', 'Tercera temporada de Kimi ni Todoke. La relación de Sawako y Kazehaya avanza con nuevas emociones, amistades y decisiones personales.', 2024, '2024-08-01', 24, 5, 3);
CALL sp_seed_temporada_contenido('My Dress-Up Darling', 'My Dress-Up Darling Temp 2', 'Sono Bisque Doll wa Koi wo Suru Season 2', 'Segunda temporada de My Dress-Up Darling. Marin y Gojo continúan creando cosplays mientras su relación se vuelve más cercana.', 2025, '2025-07-05', 24, 12, 2);
CALL sp_seed_temporada_contenido('Komi Can''t Communicate', 'Komi Can''t Communicate Temp 2', 'Komi-san wa, Komyushou desu. 2nd Season', 'Segunda temporada de Komi Can''t Communicate. Komi continúa su meta de hacer amigos con la ayuda de Tadano y nuevas situaciones escolares.', 2022, '2022-04-07', 24, 12, 2);
CALL sp_seed_temporada_contenido('Kaguya-sama: Love Is War', 'Kaguya-sama: Love Is War Temp 2', 'Kaguya-sama wa Kokurasetai? Tensai-tachi no Renai Zunousen', 'Segunda temporada de Kaguya-sama. La guerra romántica del consejo estudiantil continúa con estrategias más absurdas y momentos más sinceros.', 2020, '2020-04-11', 24, 12, 2);
CALL sp_seed_temporada_contenido('Kaguya-sama: Love Is War', 'Kaguya-sama: Love Is War - Ultra Romantic', 'Kaguya-sama wa Kokurasetai: Ultra Romantic', 'Tercera temporada de Kaguya-sama. Miyuki y Kaguya se acercan al punto decisivo de sus sentimientos durante el festival escolar.', 2022, '2022-04-09', 24, 13, 3);
CALL sp_seed_temporada_contenido('Haikyuu!!', 'Haikyuu!! Temp 2', 'Haikyuu!! Second Season', 'Segunda temporada de Haikyuu!!. Karasuno entrena y compite para superar sus límites rumbo a nuevos torneos.', 2015, '2015-10-04', 24, 25, 2);
CALL sp_seed_temporada_contenido('Haikyuu!!', 'Haikyuu!! Temp 3', 'Haikyuu!! Karasuno Koukou vs Shiratorizawa Gakuen Koukou', 'Tercera temporada de Haikyuu!!. Karasuno se enfrenta a Shiratorizawa en un partido decisivo por llegar al nacional.', 2016, '2016-10-08', 24, 10, 3);
CALL sp_seed_temporada_contenido('Haikyuu!!', 'Haikyuu!! To The Top', 'Haikyuu!! To The Top', 'Cuarta temporada de Haikyuu!!. Karasuno avanza al escenario nacional mientras Hinata y Kageyama enfrentan rivales de mayor nivel.', 2020, '2020-01-11', 24, 25, 4);
CALL sp_seed_temporada_contenido('Kuroko''s Basketball', 'Kuroko''s Basketball Temp 2', 'Kuroko no Basket 2nd Season', 'Segunda temporada de Kuroko''s Basketball. Seirin continúa enfrentando a los miembros de la Generación de los Milagros.', 2013, '2013-10-06', 24, 25, 2);
CALL sp_seed_temporada_contenido('Kuroko''s Basketball', 'Kuroko''s Basketball Temp 3', 'Kuroko no Basket 3rd Season', 'Tercera temporada de Kuroko''s Basketball. El camino de Seirin llega a su etapa más intensa contra rivales decisivos.', 2015, '2015-01-11', 24, 25, 3);
CALL sp_seed_temporada_contenido('Hajime no Ippo', 'Hajime no Ippo: New Challenger', 'Hajime no Ippo: New Challenger', 'Segunda temporada de Hajime no Ippo. Ippo sigue creciendo como boxeador mientras nuevos rivales exigen más técnica y determinación.', 2009, '2009-01-07', 24, 26, 2);
CALL sp_seed_temporada_contenido('Hajime no Ippo', 'Hajime no Ippo: Rising', 'Hajime no Ippo: Rising', 'Tercera temporada de Hajime no Ippo. Las peleas se vuelven más exigentes y el pasado de Kamogawa cobra importancia.', 2013, '2013-10-06', 24, 25, 3);
CALL sp_seed_temporada_contenido('Megalo Box', 'Megalo Box 2: Nomad', 'Nomad: Megalo Box 2', 'Segunda temporada de Megalo Box. Joe vuelve al ring cargando heridas del pasado y buscando reconciliarse consigo mismo.', 2021, '2021-04-04', 24, 13, 2);
CALL sp_seed_temporada_contenido('Free!', 'Free!: Eternal Summer', 'Free!: Eternal Summer', 'Segunda temporada de Free!. Haruka, Makoto, Rin y sus compañeros enfrentan competencias y decisiones sobre su futuro.', 2014, '2014-07-03', 24, 13, 2);
CALL sp_seed_temporada_contenido('Free!', 'Free!: Dive to the Future', 'Free!: Dive to the Future', 'Tercera temporada de Free!. Los nadadores avanzan hacia la universidad y enfrentan nuevos rivales en escenarios más competitivos.', 2018, '2018-07-12', 24, 12, 3);
CALL sp_seed_temporada_contenido('Food Wars!', 'Food Wars! The Second Plate', 'Shokugeki no Souma: Ni no Sara', 'Segunda temporada de Food Wars!. Soma enfrenta nuevos duelos culinarios en el torneo de selección de otoño.', 2016, '2016-07-02', 24, 13, 2);
CALL sp_seed_temporada_contenido('Food Wars!', 'Food Wars! The Third Plate', 'Shokugeki no Souma: San no Sara', 'Tercera temporada de Food Wars!. La élite de Totsuki y las reformas de Azami ponen a prueba a Soma y sus aliados.', 2017, '2017-10-04', 24, 24, 3);
CALL sp_seed_temporada_contenido('Food Wars!', 'Food Wars! The Fourth Plate', 'Shokugeki no Souma: Shin no Sara', 'Cuarta temporada de Food Wars!. Las batallas por el futuro de Totsuki llegan a una fase decisiva.', 2019, '2019-10-12', 24, 12, 4);
CALL sp_seed_temporada_contenido('Food Wars!', 'Food Wars! The Fifth Plate', 'Shokugeki no Souma: Gou no Sara', 'Quinta temporada de Food Wars!. Soma entra en nuevas competencias culinarias que llevan sus habilidades al límite.', 2020, '2020-04-11', 24, 13, 5);
CALL sp_seed_temporada_contenido('Dr. Stone', 'Dr. Stone: Stone Wars', 'Dr. Stone: Stone Wars', 'Segunda temporada de Dr. Stone. Senku y el Reino de la Ciencia se enfrentan al imperio de Tsukasa.', 2021, '2021-01-14', 24, 11, 2);
CALL sp_seed_temporada_contenido('Dr. Stone', 'Dr. Stone: New World', 'Dr. Stone: New World', 'Tercera temporada de Dr. Stone. Senku y sus aliados emprenden una nueva etapa para explorar el mundo y descubrir el origen de la petrificación.', 2023, '2023-04-06', 24, 22, 3);
CALL sp_seed_temporada_contenido('Dr. Stone', 'Dr. Stone: Science Future', 'Dr. Stone: Science Future', 'Cuarta temporada de Dr. Stone. El Reino de la Ciencia avanza hacia el tramo final de su misión para reconstruir la civilización.', 2025, '2025-01-09', 24, 12, 4);
CALL sp_seed_temporada_contenido('That Time I Got Reincarnated as a Slime', 'That Time I Got Reincarnated as a Slime Temp 2', 'Tensei Shitara Slime Datta Ken 2nd Season', 'Segunda temporada de Slime. Rimuru enfrenta amenazas políticas y militares mientras su nación de monstruos gana influencia.', 2021, '2021-01-12', 24, 24, 2);
CALL sp_seed_temporada_contenido('That Time I Got Reincarnated as a Slime', 'That Time I Got Reincarnated as a Slime Temp 3', 'Tensei Shitara Slime Datta Ken 3rd Season', 'Tercera temporada de Slime. Rimuru y Tempest enfrentan nuevas tensiones diplomáticas y religiosas.', 2024, '2024-04-05', 24, 24, 3);
CALL sp_seed_temporada_contenido('Re:Zero', 'Re:Zero Temp 2', 'Re:Zero kara Hajimeru Isekai Seikatsu 2nd Season', 'Segunda temporada de Re:Zero. Subaru enfrenta el Santuario, nuevas pruebas mentales y secretos sobre las brujas.', 2020, '2020-07-08', 24, 25, 2);
CALL sp_seed_temporada_contenido('Re:Zero', 'Re:Zero Temp 3', 'Re:Zero kara Hajimeru Isekai Seikatsu 3rd Season', 'Tercera temporada de Re:Zero. Subaru y sus aliados enfrentan nuevas amenazas en Priestella y una batalla de gran escala.', 2024, '2024-10-02', 24, 16, 3);
CALL sp_seed_temporada_contenido('Overlord', 'Overlord II', 'Overlord II', 'Segunda temporada de Overlord. Ainz Ooal Gown extiende su influencia mientras Nazarick manipula conflictos fuera de sus muros.', 2018, '2018-01-09', 24, 13, 2);
CALL sp_seed_temporada_contenido('Overlord', 'Overlord III', 'Overlord III', 'Tercera temporada de Overlord. Ainz da pasos más visibles para consolidar su dominio en el nuevo mundo.', 2018, '2018-07-10', 24, 13, 3);
CALL sp_seed_temporada_contenido('Overlord', 'Overlord IV', 'Overlord IV', 'Cuarta temporada de Overlord. El Reino Hechicero de Ainz avanza en sus planes políticos y militares.', 2022, '2022-07-05', 24, 13, 4);
CALL sp_seed_temporada_contenido('Konosuba', 'Konosuba Temp 2', 'Kono Subarashii Sekai ni Shukufuku wo! 2', 'Segunda temporada de Konosuba. Kazuma, Aqua, Megumin y Darkness continúan provocando caos en sus aventuras de fantasía.', 2017, '2017-01-12', 24, 10, 2);
CALL sp_seed_temporada_contenido('Konosuba', 'Konosuba Temp 3', 'Kono Subarashii Sekai ni Shukufuku wo! 3', 'Tercera temporada de Konosuba. El grupo de Kazuma se ve envuelto en nuevos problemas, nobleza y situaciones absurdas.', 2024, '2024-04-10', 24, 11, 3);
CALL sp_seed_temporada_contenido('Sword Art Online', 'Sword Art Online II', 'Sword Art Online II', 'Segunda temporada de Sword Art Online. Kirito entra en Gun Gale Online para investigar un misterioso caso relacionado con Death Gun.', 2014, '2014-07-05', 24, 24, 2);
CALL sp_seed_temporada_contenido('Sword Art Online', 'Sword Art Online: Alicization', 'Sword Art Online: Alicization', 'Tercera temporada de Sword Art Online. Kirito despierta en Underworld, un mundo virtual avanzado lleno de secretos.', 2018, '2018-10-07', 24, 24, 3);
CALL sp_seed_temporada_contenido('Sword Art Online', 'Sword Art Online: Alicization - War of Underworld', 'Sword Art Online: Alicization - War of Underworld', 'Continuación de Alicization. Underworld entra en guerra mientras Kirito y sus aliados intentan proteger ese mundo.', 2019, '2019-10-13', 24, 23, 4);
CALL sp_seed_temporada_contenido('Log Horizon', 'Log Horizon Temp 2', 'Log Horizon 2nd Season', 'Segunda temporada de Log Horizon. Shiroe y los aventureros enfrentan desafíos políticos, económicos y de exploración en Elder Tales.', 2014, '2014-10-04', 24, 25, 2);
CALL sp_seed_temporada_contenido('Log Horizon', 'Log Horizon: Destruction of the Round Table', 'Log Horizon: Entaku Houkai', 'Tercera temporada de Log Horizon. La Mesa Redonda se debilita y Akiba enfrenta nuevos conflictos internos.', 2021, '2021-01-13', 24, 12, 3);
CALL sp_seed_temporada_contenido('The Rising of the Shield Hero', 'The Rising of the Shield Hero Temp 2', 'Tate no Yuusha no Nariagari Season 2', 'Segunda temporada de The Rising of the Shield Hero. Naofumi y sus aliados enfrentan la amenaza de la Tortuga Espiritual.', 2022, '2022-04-06', 24, 13, 2);
CALL sp_seed_temporada_contenido('The Rising of the Shield Hero', 'The Rising of the Shield Hero Temp 3', 'Tate no Yuusha no Nariagari Season 3', 'Tercera temporada de The Rising of the Shield Hero. Naofumi intenta reunir héroes y reconstruir fuerzas para futuras oleadas.', 2023, '2023-10-06', 24, 12, 3);
CALL sp_seed_temporada_contenido('The Rising of the Shield Hero', 'The Rising of the Shield Hero Temp 4', 'Tate no Yuusha no Nariagari Season 4', 'Cuarta temporada de The Rising of the Shield Hero. Naofumi continúa enfrentando amenazas que ponen a prueba sus alianzas.', 2025, '2025-07-09', 24, 12, 4);
CALL sp_seed_temporada_contenido('Mushoku Tensei', 'Mushoku Tensei Temp 2', 'Mushoku Tensei II: Isekai Ittara Honki Dasu', 'Segunda temporada de Mushoku Tensei. Rudeus busca reconstruirse emocionalmente mientras inicia una nueva etapa de estudios y relaciones.', 2023, '2023-07-03', 24, 24, 2);
CALL sp_seed_temporada_contenido('Eureka Seven', 'Eureka Seven AO', 'Eureka Seven AO', 'Continuación de Eureka Seven. Ao Fukai se ve envuelto en conflictos relacionados con los Scub Coral y el legado de Eureka.', 2012, '2012-04-13', 24, 24, 2);
CALL sp_seed_temporada_contenido('Code Geass', 'Code Geass: Lelouch of the Rebellion R2', 'Code Geass: Hangyaku no Lelouch R2', 'Segunda temporada de Code Geass. Lelouch retoma su rebelión como Zero y el conflicto contra Britannia alcanza un punto decisivo.', 2008, '2008-04-06', 24, 25, 2);
CALL sp_seed_temporada_contenido('Full Metal Panic!', 'Full Metal Panic? Fumoffu', 'Full Metal Panic? Fumoffu', 'Continuación cómica de Full Metal Panic!. Sousuke intenta adaptarse a la vida escolar mientras su mentalidad militar causa problemas absurdos.', 2003, '2003-08-26', 24, 12, 2);
CALL sp_seed_temporada_contenido('Full Metal Panic!', 'Full Metal Panic! The Second Raid', 'Full Metal Panic! The Second Raid', 'Nueva etapa de Full Metal Panic!. Sousuke y Mithril enfrentan enemigos más peligrosos y conflictos personales más fuertes.', 2005, '2005-07-14', 24, 13, 3);
CALL sp_seed_temporada_contenido('Full Metal Panic!', 'Full Metal Panic! Invisible Victory', 'Full Metal Panic! Invisible Victory', 'Cuarta temporada de Full Metal Panic!. La relación entre Sousuke y Kaname se pone a prueba frente a una amenaza directa.', 2018, '2018-04-13', 24, 12, 4);
CALL sp_seed_temporada_contenido('Aldnoah.Zero', 'Aldnoah.Zero Temp 2', 'Aldnoah.Zero 2nd Season', 'Segunda temporada de Aldnoah.Zero. La guerra entre Tierra y Marte continúa con nuevas estrategias, traiciones y batallas de mechas.', 2015, '2015-01-11', 24, 12, 2);
CALL sp_seed_temporada_contenido('Fate/Stay Night: Unlimited Blade Works', 'Fate/Stay Night: Unlimited Blade Works Temp 2', 'Fate/Stay Night: Unlimited Blade Works 2nd Season', 'Segunda parte de Unlimited Blade Works. Shirou enfrenta las consecuencias de sus ideales durante la Guerra del Santo Grial.', 2015, '2015-04-05', 24, 13, 2);
CALL sp_seed_temporada_contenido('Beastars', 'Beastars Temp 2', 'Beastars 2nd Season', 'Segunda temporada de Beastars. Legoshi investiga el asesinato en la academia y enfrenta sus instintos con mayor intensidad.', 2021, '2021-01-07', 24, 12, 2);
CALL sp_seed_temporada_contenido('Beastars', 'Beastars Final Season Parte 1', 'Beastars Final Season Part 1', 'Primera parte de la temporada final de Beastars. Legoshi entra en una etapa más adulta mientras el mundo exterior revela nuevas tensiones.', 2024, '2024-12-05', 24, 12, 3);
CALL sp_seed_temporada_contenido('Beastars', 'Beastars Final Season Parte 2', 'Beastars Final Season Part 2', 'Segunda parte de la temporada final de Beastars. Los conflictos de identidad, sociedad y deseo llegan a su cierre.', 2026, '2026-03-07', 24, 12, 4);
CALL sp_seed_temporada_contenido('Grand Blue', 'Grand Blue Temp 2', 'Grand Blue Season 2', 'Segunda temporada de Grand Blue. Iori y sus amigos continúan entre la vida universitaria, el buceo y situaciones absurdas.', 2025, '2025-07-07', 24, 12, 2);
CALL sp_seed_temporada_contenido('Natsume''s Book of Friends', 'Natsume''s Book of Friends Temp 2', 'Zoku Natsume Yuujinchou', 'Segunda temporada de Natsume''s Book of Friends. Natsume sigue devolviendo nombres a yokai y entendiendo mejor sus vínculos.', 2009, '2009-01-06', 24, 13, 2);
CALL sp_seed_temporada_contenido('Natsume''s Book of Friends', 'Natsume''s Book of Friends Temp 3', 'Natsume Yuujinchou San', 'Tercera temporada de Natsume''s Book of Friends. Nuevos encuentros con espíritus profundizan la soledad y la empatía de Natsume.', 2011, '2011-07-05', 24, 13, 3);
CALL sp_seed_temporada_contenido('Natsume''s Book of Friends', 'Natsume''s Book of Friends Temp 4', 'Natsume Yuujinchou Shi', 'Cuarta temporada de Natsume''s Book of Friends. Natsume continúa protegiendo el Libro de los Amigos y descubriendo historias de yokai.', 2012, '2012-01-03', 24, 13, 4);
CALL sp_seed_temporada_contenido('Natsume''s Book of Friends', 'Natsume''s Book of Friends Temp 5', 'Natsume Yuujinchou Go', 'Quinta temporada de Natsume''s Book of Friends. La relación de Natsume con humanos y espíritus sigue creciendo con calma y sensibilidad.', 2016, '2016-10-05', 24, 11, 5);
CALL sp_seed_temporada_contenido('Natsume''s Book of Friends', 'Natsume''s Book of Friends Temp 6', 'Natsume Yuujinchou Roku', 'Sexta temporada de Natsume''s Book of Friends. Natsume enfrenta nuevas historias de memoria, pérdida y conexiones con el mundo espiritual.', 2017, '2017-04-12', 24, 11, 6);
CALL sp_seed_temporada_contenido('Natsume''s Book of Friends', 'Natsume''s Book of Friends Temp 7', 'Natsume Yuujinchou Shichi', 'Séptima temporada de Natsume''s Book of Friends. Natsume continúa encontrando yokai y personas que amplían su comprensión del mundo.', 2024, '2024-10-07', 24, 12, 7);
CALL sp_seed_temporada_contenido('Non Non Biyori', 'Non Non Biyori Repeat', 'Non Non Biyori Repeat', 'Segunda temporada de Non Non Biyori. Renge, Hotaru, Natsumi y Komari viven nuevos momentos tranquilos en Asahigaoka.', 2015, '2015-07-07', 24, 12, 2);
CALL sp_seed_temporada_contenido('Non Non Biyori', 'Non Non Biyori Nonstop', 'Non Non Biyori Nonstop', 'Tercera temporada de Non Non Biyori. La vida rural continúa con humor suave, amistad y pequeños descubrimientos cotidianos.', 2021, '2021-01-11', 24, 12, 3);

DROP PROCEDURE IF EXISTS sp_seed_temporada_contenido;
