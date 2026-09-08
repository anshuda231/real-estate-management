SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE IF NOT EXISTS `real_estate_portal`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `real_estate_portal`;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id`    INT            NOT NULL AUTO_INCREMENT,
  `name`       VARCHAR(100)   NOT NULL,
  `email`      VARCHAR(150)   NOT NULL,
  `phone`      VARCHAR(20)    NOT NULL,
  `password`   VARCHAR(255)   NOT NULL,
  `role`       ENUM('buyer','seller','admin') NOT NULL DEFAULT 'buyer',
  `created_at` TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `properties`;
CREATE TABLE `properties` (
  `property_id`   INT             NOT NULL AUTO_INCREMENT,
  `user_id`       INT             NOT NULL,
  `title`         VARCHAR(200)    NOT NULL,
  `property_type` ENUM('Apartment','Villa','House','Plot','Office') NOT NULL,
  `listing_type`  ENUM('Sale','Rent') NOT NULL,
  `price`         DECIMAL(12,2)   NOT NULL,
  `location`      VARCHAR(150)    NOT NULL,
  `area`          DECIMAL(10,2)   NOT NULL,
  `bedrooms`      INT             NOT NULL DEFAULT 0,
  `bathrooms`     INT             NOT NULL DEFAULT 0,
  `description`   TEXT            NOT NULL,
  `features`      TEXT                NULL,
  `image`         VARCHAR(300)        NULL,
  `status`        ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`property_id`),
  CONSTRAINT `fk_prop_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `inquiries`;
CREATE TABLE `inquiries` (
  `inquiry_id`  INT           NOT NULL AUTO_INCREMENT,
  `property_id` INT           NOT NULL,
  `user_id`     INT               NULL DEFAULT NULL,
  `name`        VARCHAR(100)  NOT NULL,
  `email`       VARCHAR(150)  NOT NULL,
  `phone`       VARCHAR(20)       NULL,
  `message`     TEXT          NOT NULL,
  `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`inquiry_id`),
  CONSTRAINT `fk_inq_property`
    FOREIGN KEY (`property_id`) REFERENCES `properties` (`property_id`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_inq_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO `users`
  (`name`, `email`, `phone`, `password`, `role`, `created_at`)
VALUES
('Admin NestFinder','admin@nestfinder.com','9000000001','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','admin','2024-01-01 09:00:00'),
('Rajesh Sharma','rajesh@email.com','9111111101','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','seller','2024-01-05 10:30:00'),
('Priya Patel','priya@email.com','9111111102','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','seller','2024-01-08 11:00:00'),
('Amit Kumar','amit@email.com','9111111103','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','seller','2024-01-12 14:00:00'),
('Sunita Verma','sunita@email.com','9111111104','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','seller','2024-01-15 09:30:00'),
('Rohit Desai','rohit@email.com','9222222201','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','buyer','2024-01-20 10:00:00'),
('Meena Joshi','meena@email.com','9222222202','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','buyer','2024-01-22 11:30:00'),
('Vikram Singh','vikram@email.com','9222222203','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','buyer','2024-02-01 08:00:00'),
('Anita Nair','anita@email.com','9222222204','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','buyer','2024-02-05 12:00:00'),
('Karan Mehta','karan@email.com','9222222205','$2y$10$EixZaYVK1fsbw1ZfbX3OXePaWxn96p36WQoeG6Lruj3vjPGga31lW','buyer','2024-02-10 15:00:00');

INSERT INTO `properties`
  (`user_id`,`title`,`property_type`,`listing_type`,`price`,`location`,`area`,`bedrooms`,`bathrooms`,`description`,`features`,`image`,`status`,`created_at`)
VALUES
(2,'Green Valley Apartment','Apartment','Sale',4500000.00,'Amravati',1200.00,2,2,'A beautifully designed 2BHK apartment located in the heart of Amravati. Surrounded by lush greenery, this property offers a serene living experience with modern amenities and excellent connectivity to schools, hospitals, and markets.','Lift,Parking,24x7 Security,Power Backup,Gym,Children Play Area','apartment1.jpg','active','2024-02-15 09:00:00'),
(3,'Royal Residency','Apartment','Sale',7200000.00,'Pune',1350.00,3,2,'Luxurious 3BHK apartment in one of Pune most sought-after residential areas. Features premium finishes, spacious rooms, and a world-class clubhouse. Ideal for families looking for comfort and style.','Clubhouse,Swimming Pool,Gym,Intercom,Lift,Covered Parking,Garden','apartment2.jpg','active','2024-02-18 11:00:00'),
(4,'Metro Heights Apartment','Apartment','Rent',28000.00,'Mumbai',980.00,2,1,'Well-maintained 2BHK apartment available for rent in a prime Mumbai locality. Close to metro station, shopping centres and IT parks. Perfect for working professionals and small families.','Lift,Security,Power Backup,Parking,Wi-Fi Ready','apartment3.jpg','active','2024-02-20 10:00:00'),
(2,'Blue Ridge Apartment','Apartment','Sale',3800000.00,'Nashik',1100.00,2,2,'Spacious 2BHK apartment situated in a rapidly developing area of Nashik. Surrounded by green hills and fresh air, this property is a value-for-money deal with all modern conveniences.','Lift,Parking,Security,Children Play Area,Garden,Power Backup','apartment4.jpg','active','2024-03-01 09:30:00'),
(5,'Harmony Apartment','Apartment','Rent',15000.00,'Nagpur',850.00,1,1,'Cozy 1BHK apartment available for rent in a peaceful locality of Nagpur. Fully ventilated, well-maintained building with 24-hour security. Excellent option for bachelors or newlyweds.','Lift,Security,Water Supply,Parking','apartment5.jpg','active','2024-03-05 08:00:00'),
(3,'Skyline Penthouse','Apartment','Sale',32000000.00,'Mumbai',3200.00,4,4,'An ultra-premium penthouse on the 32nd floor with breathtaking panoramic views of Mumbai. Features a private terrace, home theatre, modular kitchen, and imported marble flooring.','Private Terrace,Home Theatre,Jacuzzi,Modular Kitchen,Smart Home,Concierge,Valet Parking','apartment6.jpg','active','2024-03-08 14:00:00'),
(4,'Pinecrest Apartment','Apartment','Sale',3200000.00,'Akola',1050.00,2,1,'Affordable 2BHK apartment in a developing locality of Akola. Newly constructed, vastu-compliant layout with good natural light and ventilation. Suitable for first-time home buyers.','Parking,Security,Power Backup,Water Tank','apartment7.jpg','active','2024-03-12 10:00:00'),
(5,'Lotus Heights Apartment','Apartment','Rent',12000.00,'Nashik',780.00,1,1,'Budget-friendly 1BHK apartment for rent in Nashik. Located near the main market and bus stand, this is ideal for students and working individuals. Available immediately.','Security,Water Supply,Ground Floor,Easy Access','apartment8.jpg','active','2024-03-15 09:00:00'),
(2,'Classic Studio Apartment','Apartment','Rent',10000.00,'Nagpur',450.00,1,1,'A compact and fully furnished studio apartment in Nagpur city centre. Ideal for solo professionals. Includes attached kitchen and bathroom. All utilities included in rent.','Furnished,Wi-Fi,AC,Security,Parking','apartment9.jpg','active','2024-03-18 11:00:00'),
(3,'Sunrise Villa','Villa','Sale',8500000.00,'Nagpur',2200.00,4,3,'A magnificent 4BHK villa set in an exclusive gated community in Nagpur. Features an expansive private garden, covered car porch, and high-end Italian marble interiors.','Private Garden,Car Porch,Modular Kitchen,CCTV,Clubhouse,Swimming Pool,Power Backup','villa1.jpg','active','2024-03-20 10:30:00'),
(4,'Garden Grove Villa','Villa','Sale',12000000.00,'Pune',2800.00,5,4,'Stunning 5BHK independent villa in one of Pune finest premium localities. Spread across a generous plot with landscaped gardens, private pool, and rooftop lounge.','Swimming Pool,Rooftop Lounge,Landscaped Garden,Home Automation,4-Car Garage,CCTV','villa2.jpg','active','2024-03-22 09:00:00'),
(5,'Silver Oak Villa','Villa','Sale',11000000.00,'Nagpur',2600.00,4,4,'An exquisitely crafted 4BHK villa with Victorian architectural influences. Located in a premium gated township. Comes with a private puja room, study, and servant quarter.','Private Puja Room,Servant Quarter,Study,Garden,CCTV,Clubhouse,Covered Parking','villa3.jpg','active','2024-03-25 14:00:00'),
(2,'Emerald Villa','Villa','Rent',65000.00,'Nagpur',2100.00,4,3,'A well-appointed 4BHK villa available for rent in a serene locality of Nagpur. Fully furnished with premium appliances, this villa is perfect for families or corporates.','Fully Furnished,Modular Kitchen,Garden,Security Guard,Covered Parking,Power Backup','villa4.jpg','active','2024-04-01 10:00:00'),
(3,'Mountain View Villa','Villa','Sale',15000000.00,'Pune',3500.00,5,5,'A grand 5BHK luxury villa nestled against picturesque mountain views in Pune. Features a temperature-controlled wine cellar, infinity pool, and home cinema.','Infinity Pool,Wine Cellar,Home Cinema,Elevator,Smart Home,6-Car Garage,Helipad Ready','villa5.jpg','active','2024-04-05 11:00:00'),
(4,'Lakeview House','House','Sale',6500000.00,'Nagpur',1800.00,3,2,'A charming 3BHK independent house with a serene lake view in Nagpur. Spacious rooms, a beautiful lawn, and ample parking space. Perfect for a family looking for a peaceful neighbourhood.','Lawn,Parking,Bore Well,Solar Panel,RCC Construction,Vastu Compliant','house1.jpg','active','2024-04-08 09:00:00'),
(5,'Heritage Bungalow','House','Sale',9500000.00,'Pune',2400.00,4,3,'A majestic heritage-style bungalow with antique wooden work and classic architecture in Pune. Set in a large compound with a lush garden.','Large Compound,Antique Woodwork,Garden,Bore Well,Servant Quarter,Garage','house2.jpg','active','2024-04-10 10:30:00'),
(2,'Shanti Niwas House','House','Rent',18000.00,'Amravati',1400.00,3,2,'A comfortable 3BHK independent house available for rent in a quiet residential colony of Amravati. Has a small garden, two-wheeler parking, and all civic amenities nearby.','Garden,Parking,Water Connection,Electricity,Peaceful Area','house3.jpg','active','2024-04-12 09:00:00'),
(3,'Palm Grove House','House','Sale',5800000.00,'Nashik',1750.00,3,2,'A well-built 3BHK independent house in a coconut-palm-lined street in Nashik. Features teak-wood doors, granite kitchen counter, and a rooftop terrace.','Rooftop Terrace,Teak Wood Doors,Granite Kitchen,Parking,Bore Well','house4.jpg','active','2024-04-15 11:00:00'),
(4,'Riverside Cottage','House','Rent',12000.00,'Amravati',1050.00,2,1,'A quaint 2BHK riverside cottage available for rent. Enjoy morning walks along the riverbank. A rare find in Amravati — peaceful, green, and close to nature.','River View,Garden,Bore Well,Parking,Peaceful Locality','house5.jpg','active','2024-04-18 08:30:00'),
(5,'Sunrise Bungalow','House','Sale',4800000.00,'Wardha',1600.00,3,2,'An east-facing 3BHK bungalow in a clean residential area of Wardha. Fully vastu-compliant, RCC-framed construction with premium ceramic tile flooring.','Vastu Compliant,RCC Construction,Parking,Water Supply,Garden Space','house6.jpg','active','2024-04-20 10:00:00'),
(2,'City View Plot','Plot','Sale',2800000.00,'Akola',1500.00,0,0,'A prime residential plot in a well-laid-out layout in Akola. Clear title document, NA converted, and ready for immediate construction.','NA Converted,Clear Title,Corner Plot,Developed Area,Road Facing','plot1.jpg','active','2024-04-22 09:00:00'),
(3,'Meadow View Plot','Plot','Sale',1500000.00,'Wardha',2200.00,0,0,'A spacious residential plot on the outskirts of Wardha with scenic meadow views. RERA approved layout. Suitable for farmhouse or villa construction.','RERA Approved,Electricity Available,Road Access,Farmhouse Suitable,Clear Title','plot2.jpg','active','2024-04-25 10:00:00'),
(4,'Industrial Plot','Plot','Sale',4200000.00,'Akola',5000.00,0,0,'A large industrial-use plot in MIDC area of Akola. Suitable for warehouse, manufacturing unit, or commercial use. All utilities available.','MIDC Zone,Industrial Use,Road Access,Utilities Available,Fenced','plot3.jpg','active','2024-04-28 09:00:00'),
(5,'Commercial Plot','Plot','Sale',18000000.00,'Mumbai',3000.00,0,0,'A rare commercial plot available in a high-footfall area of Mumbai. Ideal for retail complex, office building, or hotel. FSI advantage and clear title documents.','Commercial Zone,High FSI,Clear Title,Road Facing,High Footfall Area','plot4.jpg','active','2024-05-01 11:00:00'),
(2,'Green Meadow Plot','Plot','Sale',2000000.00,'Amravati',1800.00,0,0,'A peaceful residential plot in a greenery-rich township near Amravati. RERA registered, NA converted. Ideal for building your dream home.','RERA Registered,NA Converted,Township Layout,Park Nearby,School Nearby','plot5.jpg','active','2024-05-05 09:00:00'),
(3,'Business Hub Office','Office','Rent',45000.00,'Mumbai',1800.00,0,2,'A fully furnished premium office space in the business district of Mumbai. Features a modern reception, conference room, and high-speed internet.','Furnished,Conference Room,Reception,High-Speed Internet,AC,Lift,Parking,CCTV','office1.jpg','active','2024-05-08 10:00:00'),
(4,'Tech Park Office','Office','Rent',35000.00,'Nagpur',1400.00,0,2,'A modern office unit in a thriving IT/tech park in Nagpur. Open-plan workspace, dedicated server room, and 24x7 security.','Open Plan,Server Room,24x7 Security,Cafeteria,Parking,Power Backup,Generator','office2.jpg','active','2024-05-10 09:00:00'),
(5,'Central Plaza Office','Office','Sale',25000000.00,'Mumbai',4500.00,0,4,'A landmark commercial office space in Central Mumbai iconic business plaza. Premium grade-A building with sea view. Suitable for corporate headquarters.','Grade-A Building,Sea View,Conference Rooms,Cafeteria,Valet Parking,24x7 Security,DG Backup','office3.jpg','active','2024-05-12 11:00:00'),
(2,'Downtown Office Space','Office','Rent',55000.00,'Pune',2200.00,0,3,'Premium co-working-ready office space in Pune commercial downtown. Glass-facade building, modular workstations, and a rooftop cafeteria.','Modular Workstations,Rooftop Cafeteria,Glass Facade,AC,Lift,Parking,Biometric Entry','office4.jpg','active','2024-05-15 10:00:00'),
(3,'IT Hub Office','Office','Sale',11000000.00,'Pune',3200.00,0,4,'A fully owned office unit in Pune premier IT hub. Six floors with dedicated server rooms, a training centre, and a canteen. A great long-term investment.','IT Zone,Training Centre,Server Rooms,Canteen,Lift,24x7 Security,Ample Parking,DG Backup','office5.jpg','active','2024-05-18 09:00:00');

INSERT INTO `inquiries`
  (`property_id`,`user_id`,`name`,`email`,`phone`,`message`,`created_at`)
VALUES
(1,6,'Rohit Desai','rohit@email.com','9222222201','I am interested in the Green Valley Apartment. Please share more details about the floor plan and availability.','2024-05-20 10:00:00'),
(2,7,'Meena Joshi','meena@email.com','9222222202','Kindly let me know if the Sunrise Villa is still available for sale and the final negotiable price.','2024-05-21 11:30:00'),
(3,NULL,'Arun Tiwari','arun.t@gmail.com','9333331001','I would like to visit the City View Plot this weekend. Is the title document available for review?','2024-05-22 09:00:00'),
(6,8,'Vikram Singh','vikram@email.com','9222222203','Our company is looking for furnished office space in Mumbai. Is the Business Hub Office immediately available?','2024-05-23 14:00:00'),
(4,9,'Anita Nair','anita@email.com','9222222204','We love the Royal Residency listing. Can we schedule a site visit for Saturday morning?','2024-05-24 10:00:00'),
(10,NULL,'Pooja Rathore','pooja.r@gmail.com','9444441001','I am looking for office space in Nagpur for our IT startup. Please call me at your earliest convenience.','2024-05-25 09:30:00'),
(5,10,'Karan Mehta','karan@email.com','9222222205','The Lakeview House looks perfect for our family. Please confirm if home loan assistance is available.','2024-05-26 11:00:00'),
(7,6,'Rohit Desai','rohit@email.com','9222222201','I am interested in the Garden Grove Villa in Pune. Is the price negotiable? We are serious buyers.','2024-05-27 10:00:00'),
(11,NULL,'Deepak Rao','deepak.rao@gmail.com','9555551001','Please provide details about the Blue Ridge Apartment — specifically about the project builder and RERA number.','2024-05-28 09:00:00'),
(12,7,'Meena Joshi','meena@email.com','9222222202','The Heritage Bungalow is fascinating. We would love a walkthrough this week. Please confirm timing.','2024-05-29 12:00:00'),
(15,8,'Vikram Singh','vikram@email.com','9222222203','Enquiring about the Central Plaza Office in Mumbai. We need it for a financial services firm HQ.','2024-05-30 11:00:00'),
(19,NULL,'Shalini Kapoor','shalini.k@gmail.com','9666661001','The Skyline Penthouse looks stunning. What is the monthly maintenance charge? Please call me.','2024-05-31 10:30:00'),
(20,9,'Anita Nair','anita@email.com','9222222204','We want to rent the Riverside Cottage. Is it pet-friendly? Kindly confirm availability.','2024-06-01 09:00:00'),
(25,10,'Karan Mehta','karan@email.com','9222222205','I am a developer looking at the Commercial Plot in Mumbai. Please share the FSI details and land use certificate.','2024-06-02 14:00:00'),
(27,NULL,'Nikhil Bajaj','nikhil.b@gmail.com','9777771001','Mountain View Villa in Pune looks incredible. Is there any provision for a swimming pool extension? Keen to buy.','2024-06-03 11:00:00');