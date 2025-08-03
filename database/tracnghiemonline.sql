-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th8 03, 2025 lúc 09:55 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `tracnghiemonline`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cauhoi`
--
CREATE DATABASE tracnghiemonline;
USE tracnghiemonline;
CREATE TABLE `cauhoi` (
  `macauhoi` int(11) NOT NULL,
  `noidung` varchar(500) NOT NULL,
  `dokho` int(11) NOT NULL,
  `mamonhoc` varchar(20) NOT NULL,
  `machuong` int(11) NOT NULL,
  `nguoitao` varchar(50) DEFAULT NULL,
  `trangthai` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cauhoi`
--

INSERT INTO `cauhoi` (`macauhoi`, `noidung`, `dokho`, `mamonhoc`, `machuong`, `nguoitao`, `trangthai`) VALUES
(1, 'Thẻ HTML nào dùng để tạo liên kết?', 1, 'LTW001', 1, 'GVBM001', 1),
(2, 'CSS selector nào chọn tất cả các phần tử?', 1, 'LTW001', 1, 'GVBM001', 1),
(3, 'Làm thế nào để thêm JavaScript vào HTML?', 2, 'LTW001', 1, 'GVBM001', 1),
(4, 'Framework nào phổ biến cho phát triển web?', 2, 'LTW001', 1, 'GVBM001', 1),
(5, 'Mục đích của thuộc tính alt trong thẻ img?', 1, 'LTW001', 1, 'GVBM001', 1),
(6, 'HTML5 có tính năng gì mới?', 2, 'LTW001', 1, 'GVBM001', 1),
(7, 'Thẻ meta dùng để làm gì?', 1, 'LTW001', 1, 'GVBM001', 1),
(8, 'Cách khai báo DOCTYPE trong HTML5?', 1, 'LTW001', 1, 'GVBM001', 1),
(9, 'Thẻ div thuộc loại thẻ nào?', 1, 'LTW001', 1, 'GVBM001', 1),
(10, 'Semantic HTML là gì?', 2, 'LTW001', 1, 'GVBM001', 1),
(11, 'Thẻ header dùng để làm gì?', 1, 'LTW001', 1, 'GVBM001', 1),
(12, 'Cách nhúng file CSS vào HTML?', 2, 'LTW001', 1, 'GVBM001', 1),
(13, 'Thẻ canvas trong HTML5 dùng để gì?', 3, 'LTW001', 1, 'GVBM001', 1),
(14, 'Web storage trong HTML5 là gì?', 3, 'LTW001', 1, 'GVBM001', 1),
(15, 'Geolocation API trong HTML5 làm gì?', 3, 'LTW001', 1, 'GVBM001', 1),
(16, 'Box model trong CSS bao gồm những gì?', 2, 'LTW001', 2, 'GVBM001', 1),
(17, 'Flexbox được sử dụng để làm gì?', 2, 'LTW001', 2, 'GVBM001', 1),
(18, 'Grid layout trong CSS hoạt động thế nào?', 3, 'LTW001', 2, 'GVBM001', 1),
(19, 'Cách định dạng văn bản trong CSS?', 1, 'LTW001', 2, 'GVBM001', 1),
(20, 'Thuộc tính position trong CSS có những giá trị nào?', 2, 'LTW001', 2, 'GVBM001', 1),
(21, 'Pseudo-class trong CSS là gì?', 2, 'LTW001', 2, 'GVBM001', 1),
(22, 'Cách sử dụng media queries?', 2, 'LTW001', 2, 'GVBM001', 1),
(23, 'Thuộc tính z-index dùng để làm gì?', 3, 'LTW001', 2, 'GVBM001', 1),
(24, 'Cách tạo animation trong CSS?', 3, 'LTW001', 2, 'GVBM001', 1),
(25, 'CSS preprocessor như SASS có lợi ích gì?', 3, 'LTW001', 2, 'GVBM001', 1),
(26, 'Cách sử dụng biến trong CSS?', 2, 'LTW001', 2, 'GVBM001', 1),
(27, 'Float trong CSS dùng để làm gì?', 2, 'LTW001', 2, 'GVBM001', 1),
(28, 'Cách căn giữa phần tử trong CSS?', 2, 'LTW001', 2, 'GVBM001', 1),
(29, 'Pseudo-element trong CSS là gì?', 3, 'LTW001', 2, 'GVBM001', 1),
(30, 'Cách sử dụng box-shadow trong CSS?', 2, 'LTW001', 2, 'GVBM001', 1),
(31, 'Hàm addEventListener trong JavaScript làm gì?', 2, 'LTW001', 3, 'GVBM001', 1),
(32, 'DOM là gì trong JavaScript?', 2, 'LTW001', 3, 'GVBM001', 1),
(33, 'Cách xử lý sự kiện click trong JavaScript?', 1, 'LTW001', 3, 'GVBM001', 1),
(34, 'Promise trong JavaScript là gì?', 3, 'LTW001', 3, 'GVBM001', 1),
(35, 'Async/await trong JavaScript dùng để gì?', 3, 'LTW001', 3, 'GVBM001', 1),
(36, 'Cách thao tác với DOM trong JavaScript?', 2, 'LTW001', 3, 'GVBM001', 1),
(37, 'Hàm fetch trong JavaScript dùng để gì?', 3, 'LTW001', 3, 'GVBM001', 1),
(38, 'Cách xử lý lỗi trong JavaScript?', 2, 'LTW001', 3, 'GVBM001', 1),
(39, 'Closure trong JavaScript là gì?', 3, 'LTW001', 3, 'GVBM001', 1),
(40, 'Cách sử dụng arrow function?', 2, 'LTW001', 3, 'GVBM001', 1),
(41, 'Event bubbling trong JavaScript là gì?', 3, 'LTW001', 3, 'GVBM001', 1),
(42, 'Cách sử dụng setTimeout?', 2, 'LTW001', 3, 'GVBM001', 1),
(43, 'JSON trong JavaScript là gì?', 2, 'LTW001', 3, 'GVBM001', 1),
(44, 'Cách ngăn chặn hành vi mặc định của sự kiện?', 2, 'LTW001', 3, 'GVBM001', 1),
(45, 'Cách sử dụng localStorage?', 2, 'LTW001', 3, 'GVBM001', 1),
(46, 'React là framework hay thư viện?', 3, 'LTW001', 4, 'GVBM001', 1),
(47, 'Vue.js có đặc điểm gì nổi bật?', 3, 'LTW001', 4, 'GVBM001', 1),
(48, 'Angular khác React như thế nào?', 3, 'LTW001', 4, 'GVBM001', 1),
(49, 'Redux trong React dùng để làm gì?', 3, 'LTW001', 4, 'GVBM001', 1),
(50, 'Cách sử dụng component trong React?', 2, 'LTW001', 4, 'GVBM001', 1),
(51, 'Vue Router dùng để làm gì?', 2, 'LTW001', 4, 'GVBM001', 1),
(52, 'Cách quản lý trạng thái trong Angular?', 3, 'LTW001', 4, 'GVBM001', 1),
(53, 'React Hooks là gì?', 3, 'LTW001', 4, 'GVBM001', 1),
(54, 'Cách sử dụng props trong React?', 2, 'LTW001', 4, 'GVBM001', 1),
(55, 'Vuex trong Vue.js dùng để gì?', 3, 'LTW001', 4, 'GVBM001', 1),
(56, 'Cách xử lý form trong React?', 2, 'LTW001', 4, 'GVBM001', 1),
(57, 'Next.js có lợi ích gì?', 3, 'LTW001', 4, 'GVBM001', 1),
(58, 'Cách tích hợp API trong Vue.js?', 2, 'LTW001', 4, 'GVBM001', 1),
(59, 'Svelte khác React như thế nào?', 3, 'LTW001', 4, 'GVBM001', 1),
(60, 'Cách tối ưu hóa hiệu suất trong React?', 3, 'LTW001', 4, 'GVBM001', 1),
(61, 'Responsive design được thực hiện thế nào?', 2, 'LTW001', 5, 'GVBM001', 1),
(62, 'Bootstrap là gì?', 2, 'LTW001', 5, 'GVBM001', 1),
(63, 'Cách sử dụng media queries trong CSS?', 2, 'LTW001', 5, 'GVBM001', 1),
(64, 'Breakpoint trong responsive design là gì?', 2, 'LTW001', 5, 'GVBM001', 1),
(65, 'Cách thiết kế giao diện cho mobile?', 2, 'LTW001', 5, 'GVBM001', 1),
(66, 'Tailwind CSS có ưu điểm gì?', 2, 'LTW001', 5, 'GVBM001', 1),
(67, 'Cách sử dụng rem và em trong CSS?', 2, 'LTW001', 5, 'GVBM001', 1),
(68, 'Viewport trong thiết kế web là gì?', 2, 'LTW001', 5, 'GVBM001', 1),
(69, 'Cách tối ưu hóa hình ảnh cho web?', 2, 'LTW001', 5, 'GVBM001', 1),
(70, 'Accessibility trong thiết kế web là gì?', 3, 'LTW001', 5, 'GVBM001', 1),
(71, 'Cách sử dụng ARIA trong HTML?', 3, 'LTW001', 5, 'GVBM001', 1),
(72, 'Cách thiết kế giao diện tối giản?', 2, 'LTW001', 5, 'GVBM001', 1),
(73, 'UX và UI khác nhau như thế nào?', 2, 'LTW001', 5, 'GVBM001', 1),
(74, 'Cách sử dụng wireframe trong thiết kế?', 2, 'LTW001', 5, 'GVBM001', 1),
(75, 'Công cụ nào dùng để thiết kế giao diện?', 3, 'LTW001', 5, 'GVBM001', 1),
(76, 'Cơ sở dữ liệu quan hệ là gì?', 1, 'CSDL001', 6, 'GVBM001', 1),
(77, 'Khóa chính trong bảng có vai trò gì?', 1, 'CSDL001', 6, 'GVBM001', 1),
(78, 'Khóa ngoại trong cơ sở dữ liệu là gì?', 1, 'CSDL001', 6, 'GVBM001', 1),
(79, 'RDBMS là gì?', 1, 'CSDL001', 6, 'GVBM001', 1),
(80, 'Hệ quản trị cơ sở dữ liệu nào phổ biến?', 2, 'CSDL001', 6, 'GVBM001', 1),
(81, 'Cơ sở dữ liệu phân cấp là gì?', 2, 'CSDL001', 6, 'GVBM001', 1),
(82, 'Cơ sở dữ liệu mạng là gì?', 2, 'CSDL001', 6, 'GVBM001', 1),
(83, 'Ưu điểm của cơ sở dữ liệu quan hệ?', 2, 'CSDL001', 6, 'GVBM001', 1),
(84, 'Nhược điểm của cơ sở dữ liệu quan hệ?', 2, 'CSDL001', 6, 'GVBM001', 1),
(85, 'Cách lưu trữ dữ liệu trong RDBMS?', 2, 'CSDL001', 6, 'GVBM001', 1),
(86, 'Khái niệm schema trong cơ sở dữ liệu?', 2, 'CSDL001', 6, 'GVBM001', 1),
(87, 'CSDL dùng để làm gì trong ứng dụng?', 2, 'CSDL001', 6, 'GVBM001', 1),
(88, 'Dữ liệu có cấu trúc là gì?', 1, 'CSDL001', 6, 'GVBM001', 1),
(89, 'Dữ liệu phi cấu trúc là gì?', 1, 'CSDL001', 6, 'GVBM001', 1),
(90, 'CSDL giúp gì trong quản lý doanh nghiệp?', 2, 'CSDL001', 6, 'GVBM001', 1),
(91, 'Chuẩn hóa cơ sở dữ liệu là gì?', 2, 'CSDL001', 7, 'GVBM001', 1),
(92, 'Dạng chuẩn 1NF yêu cầu gì?', 2, 'CSDL001', 7, 'GVBM001', 1),
(93, 'Dạng chuẩn 2NF yêu cầu gì?', 2, 'CSDL001', 7, 'GVBM001', 1),
(94, 'Dạng chuẩn 3NF yêu cầu gì?', 2, 'CSDL001', 7, 'GVBM001', 1),
(95, 'BCNF là gì?', 3, 'CSDL001', 7, 'GVBM001', 1),
(96, 'Lợi ích của chuẩn hóa cơ sở dữ liệu?', 2, 'CSDL001', 7, 'GVBM001', 1),
(97, 'Nhược điểm của chuẩn hóa quá mức?', 3, 'CSDL001', 7, 'GVBM001', 1),
(98, 'Cách thiết kế ERD trong cơ sở dữ liệu?', 2, 'CSDL001', 7, 'GVBM001', 1),
(99, 'Entity trong ERD là gì?', 1, 'CSDL001', 7, 'GVBM001', 1),
(100, 'Relationship trong ERD là gì?', 1, 'CSDL001', 7, 'GVBM001', 1),
(101, 'Cardinality trong ERD là gì?', 2, 'CSDL001', 7, 'GVBM001', 1),
(102, 'Cách chuyển ERD sang bảng quan hệ?', 2, 'CSDL001', 7, 'GVBM001', 1),
(103, 'Thuộc tính trong ERD là gì?', 1, 'CSDL001', 7, 'GVBM001', 1),
(104, 'Weak entity trong ERD là gì?', 3, 'CSDL001', 7, 'GVBM001', 1),
(105, 'Công cụ nào dùng để thiết kế ERD?', 2, 'CSDL001', 7, 'GVBM001', 1),
(106, 'Câu lệnh SELECT trong SQL làm gì?', 1, 'CSDL001', 8, 'GVBM001', 1),
(107, 'Cách sử dụng JOIN trong SQL?', 2, 'CSDL001', 8, 'GVBM001', 1),
(108, 'Trigger trong SQL dùng để làm gì?', 3, 'CSDL001', 8, 'GVBM001', 1),
(109, 'Câu lệnh INSERT trong SQL làm gì?', 1, 'CSDL001', 8, 'GVBM001', 1),
(110, 'Câu lệnh UPDATE trong SQL làm gì?', 1, 'CSDL001', 8, 'GVBM001', 1),
(111, 'Câu lệnh DELETE trong SQL làm gì?', 1, 'CSDL001', 8, 'GVBM001', 1),
(112, 'INNER JOIN khác LEFT JOIN như thế nào?', 2, 'CSDL001', 8, 'GVBM001', 1),
(113, 'Cách sử dụng GROUP BY trong SQL?', 2, 'CSDL001', 8, 'GVBM001', 1),
(114, 'Hàm COUNT trong SQL dùng để gì?', 1, 'CSDL001', 8, 'GVBM001', 1),
(115, 'Hàm SUM trong SQL dùng để gì?', 1, 'CSDL001', 8, 'GVBM001', 1),
(116, 'Subquery trong SQL là gì?', 3, 'CSDL001', 8, 'GVBM001', 1),
(117, 'Cách sử dụng HAVING trong SQL?', 2, 'CSDL001', 8, 'GVBM001', 1),
(118, 'Câu lệnh ORDER BY trong SQL làm gì?', 1, 'CSDL001', 8, 'GVBM001', 1),
(119, 'Stored Procedure trong SQL là gì?', 3, 'CSDL001', 8, 'GVBM001', 1),
(120, 'Cách sử dụng DISTINCT trong SQL?', 2, 'CSDL001', 8, 'GVBM001', 1),
(121, 'Transaction trong cơ sở dữ liệu là gì?', 2, 'CSDL001', 9, 'GVBM001', 1),
(122, 'ACID trong cơ sở dữ liệu nghĩa là gì?', 3, 'CSDL001', 9, 'GVBM001', 1),
(123, 'Commit trong transaction làm gì?', 2, 'CSDL001', 9, 'GVBM001', 1),
(124, 'Rollback trong transaction làm gì?', 2, 'CSDL001', 9, 'GVBM001', 1),
(125, 'Cách đảm bảo tính toàn vẹn dữ liệu?', 2, 'CSDL001', 9, 'GVBM001', 1),
(126, 'Concurrency control trong CSDL là gì?', 3, 'CSDL001', 9, 'GVBM001', 1),
(127, 'Deadlock trong cơ sở dữ liệu là gì?', 3, 'CSDL001', 9, 'GVBM001', 1),
(128, 'Khóa trong cơ sở dữ liệu là gì?', 2, 'CSDL001', 9, 'GVBM001', 1),
(129, 'Isolation trong ACID nghĩa là gì?', 3, 'CSDL001', 9, 'GVBM001', 1),
(130, 'Cách ngăn chặn SQL injection?', 3, 'CSDL001', 9, 'GVBM001', 1),
(131, 'Backup cơ sở dữ liệu là gì?', 2, 'CSDL001', 9, 'GVBM001', 1),
(132, 'Recovery trong cơ sở dữ liệu là gì?', 2, 'CSDL001', 9, 'GVBM001', 1),
(133, 'Cách mã hóa dữ liệu trong CSDL?', 3, 'CSDL001', 9, 'GVBM001', 1),
(134, 'Role trong cơ sở dữ liệu là gì?', 2, 'CSDL001', 9, 'GVBM001', 1),
(135, 'Cách phân quyền trong cơ sở dữ liệu?', 2, 'CSDL001', 9, 'GVBM001', 1),
(136, 'Index trong cơ sở dữ liệu có lợi ích gì?', 2, 'CSDL001', 10, 'GVBM001', 1),
(137, 'Query nào giúp tối ưu hóa tìm kiếm?', 3, 'CSDL001', 10, 'GVBM001', 1),
(138, 'Clustered index là gì?', 3, 'CSDL001', 10, 'GVBM001', 1),
(139, 'Non-clustered index là gì?', 3, 'CSDL001', 10, 'GVBM001', 1),
(140, 'Nhược điểm của index trong CSDL?', 3, 'CSDL001', 10, 'GVBM001', 1),
(141, 'Cách tối ưu hóa câu lệnh SELECT?', 2, 'CSDL001', 10, 'GVBM001', 1),
(142, 'Explain plan trong SQL là gì?', 3, 'CSDL001', 10, 'GVBM001', 1),
(143, 'Cách sử dụng hint trong SQL?', 3, 'CSDL001', 10, 'GVBM001', 1),
(144, 'Tối ưu hóa join trong SQL như thế nào?', 3, 'CSDL001', 10, 'GVBM001', 1),
(145, 'Covering index là gì?', 3, 'CSDL001', 10, 'GVBM001', 1),
(146, 'Cách giảm thiểu full table scan?', 2, 'CSDL001', 10, 'GVBM001', 1),
(147, 'Query execution plan là gì?', 3, 'CSDL001', 10, 'GVBM001', 1),
(148, 'Cách phân tích hiệu suất truy vấn?', 3, 'CSDL001', 10, 'GVBM001', 1),
(149, 'Index fragmentation là gì?', 3, 'CSDL001', 10, 'GVBM001', 1),
(150, 'Cách rebuild index trong SQL?', 3, 'CSDL001', 10, 'GVBM001', 1),
(151, 'Cơ sở dữ liệu NoSQL khác SQL thế nào?', 2, 'CSDL001', 11, 'GVBM001', 1),
(152, 'MongoDB thuộc loại cơ sở dữ liệu nào?', 2, 'CSDL001', 11, 'GVBM001', 1),
(153, 'Cơ sở dữ liệu tài liệu là gì?', 2, 'CSDL001', 11, 'GVBM001', 1),
(154, 'Cơ sở dữ liệu cột là gì?', 2, 'CSDL001', 11, 'GVBM001', 1),
(155, 'Cơ sở dữ liệu đồ thị là gì?', 2, 'CSDL001', 11, 'GVBM001', 1),
(156, 'Ưu điểm của NoSQL so với SQL?', 2, 'CSDL001', 11, 'GVBM001', 1),
(157, 'Nhược điểm của NoSQL?', 2, 'CSDL001', 11, 'GVBM001', 1),
(158, 'Cách lưu trữ dữ liệu trong MongoDB?', 2, 'CSDL001', 11, 'GVBM001', 1),
(159, 'BSON trong MongoDB là gì?', 2, 'CSDL001', 11, 'GVBM001', 1),
(160, 'Cách truy vấn trong MongoDB?', 2, 'CSDL001', 11, 'GVBM001', 1),
(161, 'Redis thuộc loại cơ sở dữ liệu nào?', 2, 'CSDL001', 11, 'GVBM001', 1),
(162, 'Cách sử dụng key-value store?', 2, 'CSDL001', 11, 'GVBM001', 1),
(163, 'NoSQL phù hợp với ứng dụng nào?', 2, 'CSDL001', 11, 'GVBM001', 1),
(164, 'Sharding trong NoSQL là gì?', 3, 'CSDL001', 11, 'GVBM001', 1),
(165, 'Replication trong NoSQL là gì?', 3, 'CSDL001', 11, 'GVBM001', 1),
(166, 'Cơ sở dữ liệu phân tán là gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(167, 'CAP theorem trong CSDL là gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(168, 'Consistency trong CSDL phân tán là gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(169, 'Availability trong CSDL phân tán là gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(170, 'Partition tolerance là gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(171, 'Hệ CSDL nào hỗ trợ phân tán tốt?', 3, 'CSDL001', 12, 'GVBM001', 1),
(172, 'Cách thiết kế CSDL phân tán?', 3, 'CSDL001', 12, 'GVBM001', 1),
(173, 'Sharding trong CSDL phân tán là gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(174, 'Replication trong CSDL phân tán là gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(175, 'Eventual consistency là gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(176, 'Cách xử lý xung đột dữ liệu trong CSDL phân tán?', 3, 'CSDL001', 12, 'GVBM001', 1),
(177, 'CSDL phân tán khác NoSQL như thế nào?', 3, 'CSDL001', 12, 'GVBM001', 1),
(178, 'Công cụ nào dùng cho CSDL phân tán?', 3, 'CSDL001', 12, 'GVBM001', 1),
(179, 'Hadoop HDFS dùng để làm gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(180, 'Apache Cassandra có đặc điểm gì?', 3, 'CSDL001', 12, 'GVBM001', 1),
(181, 'Trí tuệ nhân tạo là gì?', 1, 'TTNT001', 13, 'GVBM001', 1),
(182, 'Phân biệt AI, ML và DL?', 2, 'TTNT001', 13, 'GVBM001', 1),
(183, 'Ứng dụng thực tiễn của AI là gì?', 1, 'TTNT001', 13, 'GVBM001', 1),
(184, 'AI mạnh và AI yếu khác nhau thế nào?', 2, 'TTNT001', 13, 'GVBM001', 1),
(185, 'Lịch sử phát triển của AI?', 1, 'TTNT001', 13, 'GVBM001', 1),
(186, 'Turing Test trong AI là gì?', 2, 'TTNT001', 13, 'GVBM001', 1),
(187, 'AI được ứng dụng trong ngành nào?', 1, 'TTNT001', 13, 'GVBM001', 1),
(188, 'Ethics trong AI là gì?', 3, 'TTNT001', 13, 'GVBM001', 1),
(189, 'AI có thể thay thế con người không?', 3, 'TTNT001', 13, 'GVBM001', 1),
(190, 'AI cần dữ liệu gì để hoạt động?', 2, 'TTNT001', 13, 'GVBM001', 1),
(191, 'Machine Learning là gì?', 1, 'TTNT001', 13, 'GVBM001', 1),
(192, 'Deep Learning là gì?', 2, 'TTNT001', 13, 'GVBM001', 1),
(193, 'AI có những nhánh nào?', 2, 'TTNT001', 13, 'GVBM001', 1),
(194, 'Cách AI học từ dữ liệu?', 2, 'TTNT001', 13, 'GVBM001', 1),
(195, 'Big Data liên quan đến AI thế nào?', 2, 'TTNT001', 13, 'GVBM001', 1),
(196, 'Học có giám sát là gì?', 2, 'TTNT001', 14, 'GVBM001', 1),
(197, 'Học không giám sát là gì?', 2, 'TTNT001', 14, 'GVBM001', 1),
(198, 'Học tăng cường là gì?', 2, 'TTNT001', 14, 'GVBM001', 1),
(199, 'Hồi quy tuyến tính thuộc loại học nào?', 2, 'TTNT001', 14, 'GVBM001', 1),
(200, 'Phân loại trong học máy là gì?', 2, 'TTNT001', 14, 'GVBM001', 1),
(201, 'Overfitting trong học máy là gì?', 3, 'TTNT001', 14, 'GVBM001', 1),
(202, 'Underfitting trong học máy là gì?', 3, 'TTNT001', 14, 'GVBM001', 1),
(203, 'Cách đánh giá mô hình học máy?', 2, 'TTNT001', 14, 'GVBM001', 1),
(204, 'Cross-validation trong học máy là gì?', 3, 'TTNT001', 14, 'GVBM001', 1),
(205, 'Decision Tree trong học máy là gì?', 2, 'TTNT001', 14, 'GVBM001', 1),
(206, 'Random Forest là gì?', 3, 'TTNT001', 14, 'GVBM001', 1),
(207, 'SVM trong học máy là gì?', 3, 'TTNT001', 14, 'GVBM001', 1),
(208, 'K-means clustering là gì?', 2, 'TTNT001', 14, 'GVBM001', 1),
(209, 'Cách xử lý dữ liệu mất cân bằng?', 3, 'TTNT001', 14, 'GVBM001', 1),
(210, 'Feature engineering trong học máy là gì?', 3, 'TTNT001', 14, 'GVBM001', 1),
(211, 'Mạng nơ-ron nhân tạo hoạt động thế nào?', 3, 'TTNT001', 15, 'GVBM001', 1),
(212, 'Hàm kích hoạt trong mạng nơ-ron là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(213, 'Backpropagation trong học sâu là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(214, 'CNN trong học sâu là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(215, 'RNN trong học sâu là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(216, 'LSTM trong mạng nơ-ron là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(217, 'GAN trong học sâu là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(218, 'Dropout trong mạng nơ-ron là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(219, 'Batch normalization là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(220, 'Cách tối ưu hóa mạng nơ-ron?', 3, 'TTNT001', 15, 'GVBM001', 1),
(221, 'Gradient descent là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(222, 'Adam optimizer là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(223, 'Transfer learning trong học sâu là gì?', 3, 'TTNT001', 15, 'GVBM001', 1),
(224, 'Cách xử lý overfitting trong học sâu?', 3, 'TTNT001', 15, 'GVBM001', 1),
(225, 'TensorFlow là gì?', 2, 'TTNT001', 15, 'GVBM001', 1),
(226, 'NLP được ứng dụng trong lĩnh vực nào?', 2, 'TTNT001', 16, 'GVBM001', 1),
(227, 'Chatbot sử dụng công nghệ gì?', 2, 'TTNT001', 16, 'GVBM001', 1),
(228, 'Tokenization trong NLP là gì?', 2, 'TTNT001', 16, 'GVBM001', 1),
(229, 'Word embedding trong NLP là gì?', 3, 'TTNT001', 16, 'GVBM001', 1),
(230, 'BERT trong NLP là gì?', 3, 'TTNT001', 16, 'GVBM001', 1),
(231, 'Sentiment analysis trong NLP là gì?', 2, 'TTNT001', 16, 'GVBM001', 1),
(232, 'POS tagging trong NLP là gì?', 3, 'TTNT001', 16, 'GVBM001', 1),
(233, 'Named Entity Recognition là gì?', 3, 'TTNT001', 16, 'GVBM001', 1),
(234, 'Machine translation trong NLP là gì?', 2, 'TTNT001', 16, 'GVBM001', 1),
(235, 'Cách xử lý văn bản trong NLP?', 2, 'TTNT001', 16, 'GVBM001', 1),
(236, 'TF-IDF trong NLP là gì?', 3, 'TTNT001', 16, 'GVBM001', 1),
(237, 'Ứng dụng của NLP trong chatbot?', 2, 'TTNT001', 16, 'GVBM001', 1),
(238, 'Cách huấn luyện mô hình NLP?', 3, 'TTNT001', 16, 'GVBM001', 1),
(239, 'Stop words trong NLP là gì?', 2, 'TTNT001', 16, 'GVBM001', 1),
(240, 'Cách đánh giá mô hình NLP?', 3, 'TTNT001', 16, 'GVBM001', 1),
(241, 'Thị giác máy tính dùng để làm gì?', 2, 'TTNT001', 17, 'GVBM001', 1),
(242, 'CNN trong thị giác máy tính là gì?', 3, 'TTNT001', 17, 'GVBM001', 1),
(243, 'Object detection trong thị giác máy tính là gì?', 3, 'TTNT001', 17, 'GVBM001', 1),
(244, 'Image segmentation là gì?', 3, 'TTNT001', 17, 'GVBM001', 1),
(245, 'YOLO trong thị giác máy tính là gì?', 3, 'TTNT001', 17, 'GVBM001', 1),
(246, 'Cách nhận diện khuôn mặt?', 3, 'TTNT001', 17, 'GVBM001', 1),
(247, 'Ứng dụng của thị giác máy tính?', 2, 'TTNT001', 17, 'GVBM001', 1),
(248, 'Cách xử lý hình ảnh trong thị giác máy tính?', 2, 'TTNT001', 17, 'GVBM001', 1),
(249, 'Feature extraction trong thị giác máy tính là gì?', 3, 'TTNT001', 17, 'GVBM001', 1),
(250, 'OpenCV dùng để làm gì?', 2, 'TTNT001', 17, 'GVBM001', 1),
(251, 'Cách huấn luyện mô hình thị giác máy tính?', 3, 'TTNT001', 17, 'GVBM001', 1),
(252, 'Data augmentation trong thị giác máy tính là gì?', 3, 'TTNT001', 17, 'GVBM001', 1),
(253, 'Cách đánh giá mô hình thị giác máy tính?', 3, 'TTNT001', 17, 'GVBM001', 1),
(254, 'Transfer learning trong thị giác máy tính là gì?', 3, 'TTNT001', 17, 'GVBM001', 1),
(255, 'VGG16 là gì?', 3, 'TTNT001', 17, 'GVBM001', 1),
(256, 'Hệ thống chuyên gia có đặc điểm gì?', 3, 'TTNT001', 18, 'GVBM001', 1),
(257, 'Hệ thống chuyên gia dùng để làm gì?', 2, 'TTNT001', 18, 'GVBM001', 1),
(258, 'Knowledge base trong hệ thống chuyên gia là gì?', 2, 'TTNT001', 18, 'GVBM001', 1),
(259, 'Inference engine trong hệ thống chuyên gia là gì?', 3, 'TTNT001', 18, 'GVBM001', 1),
(260, 'Ứng dụng của hệ thống chuyên gia?', 2, 'TTNT001', 18, 'GVBM001', 1),
(261, 'Hệ thống chuyên gia khác AI như thế nào?', 3, 'TTNT001', 18, 'GVBM001', 1),
(262, 'Rule-based system là gì?', 2, 'TTNT001', 18, 'GVBM001', 1),
(263, 'Cách xây dựng hệ thống chuyên gia?', 3, 'TTNT001', 18, 'GVBM001', 1),
(264, 'Hạn chế của hệ thống chuyên gia?', 3, 'TTNT001', 18, 'GVBM001', 1),
(265, 'Hệ thống chuyên gia trong y tế là gì?', 2, 'TTNT001', 18, 'GVBM001', 1),
(266, 'Expert system khác học máy như thế nào?', 3, 'TTNT001', 18, 'GVBM001', 1),
(267, 'Cách cập nhật knowledge base?', 2, 'TTNT001', 18, 'GVBM001', 1),
(268, 'Ứng dụng của hệ thống chuyên gia trong tài chính?', 2, 'TTNT001', 18, 'GVBM001', 1),
(269, 'Hệ thống chuyên gia dùng ngôn ngữ nào?', 2, 'TTNT001', 18, 'GVBM001', 1),
(270, 'Công cụ nào dùng để xây dựng hệ thống chuyên gia?', 3, 'TTNT001', 18, 'GVBM001', 1),
(271, 'AI được ứng dụng trong y tế như thế nào?', 2, 'TTNT001', 19, 'GVBM001', 1),
(272, 'AI trong tự động lái xe hoạt động thế nào?', 3, 'TTNT001', 19, 'GVBM001', 1),
(273, 'AI trong thương mại điện tử làm gì?', 2, 'TTNT001', 19, 'GVBM001', 1),
(274, 'AI trong giáo dục được ứng dụng ra sao?', 2, 'TTNT001', 19, 'GVBM001', 1),
(275, 'AI trong an ninh mạng làm gì?', 3, 'TTNT001', 19, 'GVBM001', 1),
(276, 'AI trong nông nghiệp được ứng dụng thế nào?', 2, 'TTNT001', 19, 'GVBM001', 1),
(277, 'AI trong sản xuất hoạt động ra sao?', 3, 'TTNT001', 19, 'GVBM001', 1),
(278, 'AI trong tài chính làm gì?', 2, 'TTNT001', 19, 'GVBM001', 1),
(279, 'AI trong logistics được ứng dụng thế nào?', 2, 'TTNT001', 19, 'GVBM001', 1),
(280, 'AI trong chăm sóc khách hàng làm gì?', 2, 'TTNT001', 19, 'GVBM001', 1),
(281, 'AI trong trò chơi điện tử hoạt động ra sao?', 2, 'TTNT001', 19, 'GVBM001', 1),
(282, 'Hạn chế của AI trong thực tiễn?', 3, 'TTNT001', 19, 'GVBM001', 1),
(283, 'Cách đánh giá hiệu quả ứng dụng AI?', 3, 'TTNT001', 19, 'GVBM001', 1),
(284, 'AI trong xử lý hình ảnh y khoa là gì?', 3, 'TTNT001', 19, 'GVBM001', 1),
(285, 'AI trong dự báo thời tiết hoạt động thế nào?', 3, 'TTNT001', 19, 'GVBM001', 1),
(286, 'Dart là ngôn ngữ lập trình loại nào?', 1, 'LTDD001', 20, 'GVBM001', 1),
(287, 'Flutter khác React Native như thế nào?', 2, 'LTDD001', 20, 'GVBM001', 1),
(288, 'Ưu điểm của Flutter trong lập trình di động?', 1, 'LTDD001', 20, 'GVBM001', 1),
(289, 'Dart hỗ trợ lập trình hướng đối tượng không?', 1, 'LTDD001', 20, 'GVBM001', 1),
(290, 'Flutter dùng để phát triển ứng dụng gì?', 1, 'LTDD001', 20, 'GVBM001', 1),
(291, 'Hot reload trong Flutter là gì?', 2, 'LTDD001', 20, 'GVBM001', 1),
(292, 'Flutter SDK bao gồm những gì?', 2, 'LTDD001', 20, 'GVBM001', 1),
(293, 'Cách cài đặt Flutter trên máy tính?', 1, 'LTDD001', 20, 'GVBM001', 1),
(294, 'Công cụ nào dùng để phát triển Flutter?', 2, 'LTDD001', 20, 'GVBM001', 1),
(295, 'Flutter có hỗ trợ đa nền tảng không?', 1, 'LTDD001', 20, 'GVBM001', 1),
(296, 'Nhược điểm của Flutter là gì?', 2, 'LTDD001', 20, 'GVBM001', 1),
(297, 'Flutter khác native app như thế nào?', 2, 'LTDD001', 20, 'GVBM001', 1),
(298, 'Dart có hỗ trợ async programming không?', 2, 'LTDD001', 20, 'GVBM001', 1),
(299, 'Widget trong Flutter là gì?', 1, 'LTDD001', 20, 'GVBM001', 1),
(300, 'Cách tạo dự án Flutter mới?', 1, 'LTDD001', 20, 'GVBM001', 1),
(301, 'Cú pháp biến trong Dart là gì?', 1, 'LTDD001', 21, 'GVBM001', 1),
(302, 'Async/await trong Dart dùng để làm gì?', 2, 'LTDD001', 21, 'GVBM001', 1),
(303, 'Class trong Dart được định nghĩa thế nào?', 1, 'LTDD001', 21, 'GVBM001', 1),
(304, 'Hàm trong Dart hoạt động ra sao?', 1, 'LTDD001', 21, 'GVBM001', 1),
(305, 'Null safety trong Dart là gì?', 2, 'LTDD001', 21, 'GVBM001', 1),
(306, 'Cách sử dụng list trong Dart?', 1, 'LTDD001', 21, 'GVBM001', 1),
(307, 'Map trong Dart là gì?', 1, 'LTDD001', 21, 'GVBM001', 1),
(308, 'Cách xử lý lỗi trong Dart?', 2, 'LTDD001', 21, 'GVBM001', 1),
(309, 'Future trong Dart là gì?', 2, 'LTDD001', 21, 'GVBM001', 1),
(310, 'Stream trong Dart là gì?', 3, 'LTDD001', 21, 'GVBM001', 1),
(311, 'Cách sử dụng const trong Dart?', 1, 'LTDD001', 21, 'GVBM001', 1),
(312, 'Mixin trong Dart là gì?', 3, 'LTDD001', 21, 'GVBM001', 1),
(313, 'Cách sử dụng enum trong Dart?', 2, 'LTDD001', 21, 'GVBM001', 1),
(314, 'Extension trong Dart là gì?', 3, 'LTDD001', 21, 'GVBM001', 1),
(315, 'Cách sử dụng generic trong Dart?', 3, 'LTDD001', 21, 'GVBM001', 1),
(316, 'StatelessWidget và StatefulWidget khác nhau ra sao?', 2, 'LTDD001', 22, 'GVBM001', 1),
(317, 'Cách tạo giao diện với Flutter?', 1, 'LTDD001', 22, 'GVBM001', 1),
(318, 'MaterialApp trong Flutter là gì?', 1, 'LTDD001', 22, 'GVBM001', 1),
(319, 'Scaffold trong Flutter dùng để gì?', 1, 'LTDD001', 22, 'GVBM001', 1),
(320, 'Cách sử dụng Row và Column trong Flutter?', 1, 'LTDD001', 22, 'GVBM001', 1),
(321, 'Stack trong Flutter dùng để gì?', 2, 'LTDD001', 22, 'GVBM001', 1),
(322, 'Cách sử dụng ListView trong Flutter?', 2, 'LTDD001', 22, 'GVBM001', 1),
(323, 'Container trong Flutter là gì?', 1, 'LTDD001', 22, 'GVBM001', 1),
(324, 'Cách sử dụng Text widget trong Flutter?', 1, 'LTDD001', 22, 'GVBM001', 1),
(325, 'GestureDetector trong Flutter là gì?', 2, 'LTDD001', 22, 'GVBM001', 1),
(326, 'Cách tạo button trong Flutter?', 1, 'LTDD001', 22, 'GVBM001', 1),
(327, 'Cách sử dụng Image widget?', 1, 'LTDD001', 22, 'GVBM001', 1),
(328, 'AppBar trong Flutter dùng để gì?', 1, 'LTDD001', 22, 'GVBM001', 1),
(329, 'Cách sử dụng Navigator trong Flutter?', 2, 'LTDD001', 22, 'GVBM001', 1),
(330, 'Cách tạo animation trong Flutter?', 3, 'LTDD001', 22, 'GVBM001', 1),
(331, 'Provider trong Flutter dùng để làm gì?', 2, 'LTDD001', 23, 'GVBM001', 1),
(332, 'Bloc pattern trong Flutter hoạt động thế nào?', 3, 'LTDD001', 23, 'GVBM001', 1),
(333, 'Riverpod trong Flutter là gì?', 3, 'LTDD001', 23, 'GVBM001', 1),
(334, 'Cách quản lý trạng thái toàn cục?', 2, 'LTDD001', 23, 'GVBM001', 1),
(335, 'GetX trong Flutter dùng để gì?', 3, 'LTDD001', 23, 'GVBM001', 1),
(336, 'Cách sử dụng StreamBuilder trong Flutter?', 3, 'LTDD001', 23, 'GVBM001', 1),
(337, 'Cách xử lý sự kiện trong Flutter?', 2, 'LTDD001', 23, 'GVBM001', 1),
(338, 'Cách sử dụng InheritedWidget?', 3, 'LTDD001', 23, 'GVBM001', 1),
(339, 'ChangeNotifier trong Flutter là gì?', 2, 'LTDD001', 23, 'GVBM001', 1),
(340, 'Cách tối ưu hóa trạng thái trong Flutter?', 3, 'LTDD001', 23, 'GVBM001', 1),
(341, 'StatefulWidget dùng khi nào?', 2, 'LTDD001', 23, 'GVBM001', 1),
(342, 'Cách sử dụng setState trong Flutter?', 2, 'LTDD001', 23, 'GVBM001', 1),
(343, 'ValueNotifier trong Flutter là gì?', 3, 'LTDD001', 23, 'GVBM001', 1),
(344, 'Cách sử dụng FutureBuilder trong Flutter?', 2, 'LTDD001', 23, 'GVBM001', 1),
(345, 'Cách xử lý lỗi trong quản lý trạng thái?', 3, 'LTDD001', 23, 'GVBM001', 1),
(346, 'Cách gọi API trong Flutter?', 2, 'LTDD001', 24, 'GVBM001', 1),
(347, 'Firebase tích hợp với Flutter như thế nào?', 3, 'LTDD001', 24, 'GVBM001', 1),
(348, 'Package http trong Flutter dùng để gì?', 2, 'LTDD001', 24, 'GVBM001', 1),
(349, 'Cách sử dụng Dio trong Flutter?', 2, 'LTDD001', 24, 'GVBM001', 1),
(350, 'Cách tích hợp Firestore trong Flutter?', 3, 'LTDD001', 24, 'GVBM001', 1),
(351, 'REST API trong Flutter hoạt động thế nào?', 2, 'LTDD001', 24, 'GVBM001', 1),
(352, 'Cách lưu trữ dữ liệu cục bộ trong Flutter?', 2, 'LTDD001', 24, 'GVBM001', 1),
(353, 'SharedPreferences trong Flutter là gì?', 2, 'LTDD001', 24, 'GVBM001', 1),
(354, 'Cách sử dụng SQLite trong Flutter?', 3, 'LTDD001', 24, 'GVBM001', 1),
(355, 'Hive trong Flutter là gì?', 3, 'LTDD001', 24, 'GVBM001', 1),
(356, 'Cách tích hợp push notification trong Flutter?', 3, 'LTDD001', 24, 'GVBM001', 1),
(357, 'Cách sử dụng GraphQL trong Flutter?', 3, 'LTDD001', 24, 'GVBM001', 1),
(358, 'Cách xử lý dữ liệu JSON trong Flutter?', 2, 'LTDD001', 24, 'GVBM001', 1),
(359, 'Cách tích hợp Google Maps trong Flutter?', 3, 'LTDD001', 24, 'GVBM001', 1),
(360, 'Cách tích hợp authentication trong Flutter?', 3, 'LTDD001', 24, 'GVBM001', 1),
(361, 'Cách kiểm thử unit test trong Flutter?', 2, 'LTDD001', 25, 'GVBM001', 1),
(362, 'Công cụ nào dùng để triển khai ứng dụng Flutter?', 3, 'LTDD001', 25, 'GVBM001', 1),
(363, 'Integration test trong Flutter là gì?', 3, 'LTDD001', 25, 'GVBM001', 1),
(364, 'Widget test trong Flutter là gì?', 2, 'LTDD001', 25, 'GVBM001', 1),
(365, 'Cách sử dụng package test trong Flutter?', 2, 'LTDD001', 25, 'GVBM001', 1),
(366, 'Cách debug ứng dụng Flutter?', 2, 'LTDD001', 25, 'GVBM001', 1),
(367, 'Flutter DevTools dùng để gì?', 3, 'LTDD001', 25, 'GVBM001', 1),
(368, 'Cách tối ưu hóa hiệu suất ứng dụng Flutter?', 3, 'LTDD001', 25, 'GVBM001', 1),
(369, 'Cách xuất bản ứng dụng lên Play Store?', 3, 'LTDD001', 25, 'GVBM001', 1),
(370, 'Cách xuất bản ứng dụng lên App Store?', 3, 'LTDD001', 25, 'GVBM001', 1),
(371, 'Code signing trong Flutter là gì?', 3, 'LTDD001', 25, 'GVBM001', 1),
(372, 'Cách xử lý lỗi trong ứng dụng Flutter?', 2, 'LTDD001', 25, 'GVBM001', 1),
(373, 'Cách tối ưu hóa kích thước ứng dụng Flutter?', 3, 'LTDD001', 25, 'GVBM001', 1),
(374, 'Cách sử dụng profiling trong Flutter?', 3, 'LTDD001', 25, 'GVBM001', 1),
(375, 'Cách kiểm tra hiệu suất ứng dụng Flutter?', 3, 'LTDD001', 25, 'GVBM001', 1),
(376, 'Trong trí tuệ nhân tạo, thuật toán nào sau đây được sử dụng để tìm kiếm tối ưu trong không gian trạng thái?\n', 1, 'TTNT001', 14, 'GVBM001', 1),
(377, 'Thuật toán học máy nào sau đây thường được sử dụng cho bài toán phân loại?\n', 1, 'TTNT001', 15, 'GVBM001', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cautraloi`
--

CREATE TABLE `cautraloi` (
  `macautl` int(11) NOT NULL,
  `macauhoi` int(11) NOT NULL,
  `noidungtl` varchar(500) NOT NULL,
  `ladapan` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cautraloi`
--

INSERT INTO `cautraloi` (`macautl`, `macauhoi`, `noidungtl`, `ladapan`) VALUES
(1, 1, 'p', 0),
(2, 1, 'a', 1),
(3, 1, 'div', 0),
(4, 1, 'span', 0),
(5, 2, '.class', 0),
(6, 2, 'body', 0),
(7, 2, '#id', 0),
(8, 2, '*', 1),
(9, 3, 'script', 1),
(10, 3, 'code', 0),
(11, 3, 'js', 0),
(12, 3, 'javascript', 0),
(13, 4, 'React', 1),
(14, 4, 'Vue', 0),
(15, 4, 'jQuery', 0),
(16, 4, 'Bootstrap', 0),
(17, 5, 'Làm ảnh rõ hơn', 0),
(18, 5, 'Hiển thị mô tả thay thế khi ảnh lỗi', 1),
(19, 5, 'Căn giữa ảnh', 0),
(20, 5, 'Tạo đường viền cho ảnh', 0),
(21, 6, 'Bảng điều khiển', 0),
(22, 6, 'Video, audio, canvas', 1),
(23, 6, 'Trình biên dịch', 0),
(24, 6, 'Hệ quản trị', 0),
(25, 7, 'Thêm hình ảnh', 0),
(26, 7, 'Cung cấp thông tin cho trình duyệt và công cụ tìm kiếm', 1),
(27, 7, 'Tạo bố cục', 0),
(28, 7, 'Định dạng văn bản', 0),
(29, 8, 'doctype html5', 0),
(30, 8, '!DOCTYPE HTML5', 0),
(31, 8, 'html doctype', 0),
(32, 8, '!DOCTYPE html', 1),
(33, 9, 'Biểu mẫu', 0),
(34, 9, 'Đa phương tiện', 0),
(35, 9, 'Khối (block)', 1),
(36, 9, 'Dòng (inline)', 0),
(37, 10, 'HTML dùng để thiết kế database', 0),
(38, 10, 'Ngôn ngữ máy', 0),
(39, 10, 'HTML có ý nghĩa giúp máy đọc hiểu nội dung', 1),
(40, 10, 'Công nghệ xử lý ảnh', 0),
(41, 11, 'Định nghĩa đường dẫn', 0),
(42, 11, 'Chứa tiêu đề và nội dung giới thiệu', 1),
(43, 11, 'Chứa footer', 0),
(44, 11, 'Chứa ảnh', 0),
(45, 12, 'Dán trực tiếp vào body', 0),
(46, 12, 'Dùng thẻ style trong footer', 0),
(47, 12, 'Dùng thẻ script', 0),
(48, 12, 'Dùng thẻ link trong head', 1),
(49, 13, 'Vẽ đồ họa bằng JavaScript', 1),
(50, 13, 'Phát video', 0),
(51, 13, 'Định dạng văn bản', 0),
(52, 13, 'Tạo liên kết', 0),
(53, 14, 'Lưu code CSS', 0),
(54, 14, 'Lưu trữ dữ liệu trên trình duyệt', 1),
(55, 14, 'Tạo API', 0),
(56, 14, 'Lưu trữ file ảnh', 0),
(57, 15, 'Tăng tốc website', 0),
(58, 15, 'Xác định vị trí người dùng', 1),
(59, 15, 'Chụp ảnh màn hình', 0),
(60, 15, 'Ẩn địa chỉ IP', 0),
(61, 16, 'border, text, image, layout', 0),
(62, 16, 'margin, border, padding, content', 1),
(63, 16, 'outline, space, tag, text', 0),
(64, 16, 'box, item, flex, float', 0),
(65, 17, 'Định dạng màu nền', 0),
(66, 17, 'Tạo hiệu ứng hover', 0),
(67, 17, 'Xếp phần tử theo hàng hoặc cột', 1),
(68, 17, 'Tạo bảng', 0),
(69, 18, 'Ẩn hiện phần tử', 0),
(70, 18, 'Tạo danh sách thả', 0),
(71, 18, 'Chia trang thành lưới hàng và cột', 1),
(72, 18, 'Căn chỉnh văn bản', 0),
(73, 19, 'Dùng file ảnh', 0),
(74, 19, 'Dùng thuộc tính như font-size, color', 1),
(75, 19, 'Gõ trực tiếp trong trình duyệt', 0),
(76, 19, 'Dùng thẻ input', 0),
(77, 20, 'static, relative, absolute, fixed, sticky', 1),
(78, 20, 'margin, padding, border, float', 0),
(79, 20, 'center, top, bottom, flex', 0),
(80, 20, 'block, inline, table, list', 0),
(81, 21, 'Bộ chọn trong SQL', 0),
(82, 21, 'Thẻ đặc biệt trong HTML', 0),
(83, 21, 'Lớp giả mô tả trạng thái phần tử', 1),
(84, 21, 'Lệnh JS', 0),
(85, 22, 'Tạo iframe', 0),
(86, 22, 'Dùng @media để thay đổi CSS theo màn hình', 1),
(87, 22, 'Thêm class vào phần tử', 0),
(88, 22, 'Dùng alert trong JS', 0),
(89, 23, 'Căn giữa phần tử', 0),
(90, 23, 'Tăng kích thước ảnh', 0),
(91, 23, 'Ẩn phần tử', 0),
(92, 23, 'Xác định thứ tự chồng lớp phần tử', 1),
(93, 24, 'Thêm video mp4', 0),
(94, 24, 'Gắn JS inline', 0),
(95, 24, 'Dùng @keyframes và animation', 1),
(96, 24, 'Dùng thẻ gif', 0),
(97, 25, 'Chạy server tốt hơn', 0),
(98, 25, 'Viết HTML dễ hơn', 0),
(99, 25, 'Tăng tốc độ mạng', 0),
(100, 25, 'Sử dụng biến, lồng, tái sử dụng dễ dàng', 1),
(101, 26, 'Dùng cú pháp --ten: value', 1),
(102, 26, 'Dùng trong comment', 0),
(103, 26, 'Gán trong HTML', 0),
(104, 26, 'Khai báo bằng let', 0),
(105, 27, 'Ẩn phần tử', 0),
(106, 27, 'Thêm đường viền', 0),
(107, 27, 'Tăng font chữ', 0),
(108, 27, 'Căn phần tử sang trái/phải', 1),
(109, 28, 'Dùng margin: auto', 1),
(110, 28, 'float: center', 0),
(111, 28, 'text-align: justify', 0),
(112, 28, 'padding: center', 0),
(113, 29, 'Tạo biến', 0),
(114, 29, 'Tạo phần tử ảo như ::before, ::after', 1),
(115, 29, 'Ẩn class', 0),
(116, 29, 'Thay đổi ID', 0),
(117, 30, 'Dùng text-shadow', 0),
(118, 30, 'Dùng box-shadow: x y blur color', 1),
(119, 30, 'Dùng shadow-box: true', 0),
(120, 30, 'Thêm vào class box', 0),
(121, 31, 'Gắn lắng nghe sự kiện cho phần tử', 1),
(122, 31, 'Tạo hiệu ứng hoạt hình', 0),
(123, 31, 'Tạo thẻ HTML mới', 0),
(124, 31, 'Lấy dữ liệu từ server', 0),
(125, 32, 'Hệ thống phân quyền trong JS', 0),
(126, 32, 'Cấu trúc đối tượng mô tả tài liệu HTML', 1),
(127, 32, 'Cơ sở dữ liệu của trình duyệt', 0),
(128, 32, 'Cách thiết kế layout trang web', 0),
(129, 33, 'Dùng onclick hoặc addEventListener', 1),
(130, 33, 'Dùng innerHTML', 0),
(131, 33, 'Dùng localStorage', 0),
(132, 33, 'Dùng fetch', 0),
(133, 34, 'Đối tượng đại diện cho kết quả bất đồng bộ', 1),
(134, 34, 'Cách gọi API đồng bộ', 0),
(135, 34, 'Một hàm vòng lặp vô hạn', 0),
(136, 34, 'Biến toàn cục trong JS', 0),
(137, 35, 'Tối ưu tốc độ DOM', 0),
(138, 35, 'Viết CSS linh hoạt hơn', 0),
(139, 35, 'Gọi hàm khi trang tải xong', 0),
(140, 35, 'Xử lý bất đồng bộ dễ đọc hơn Promise', 1),
(141, 36, 'Dùng innerText cho style', 0),
(142, 36, 'Dùng SQL trong JS', 0),
(143, 36, 'Dùng document.querySelector, getElementById,...', 1),
(144, 36, 'Dùng fetch API', 0),
(145, 37, 'Lấy dữ liệu từ API (HTTP)', 1),
(146, 37, 'Xóa phần tử HTML', 0),
(147, 37, 'Tạo form nhập liệu', 0),
(148, 37, 'Tạo biểu đồ thống kê', 0),
(149, 38, 'Dùng try...catch', 1),
(150, 38, 'Dùng async...await', 0),
(151, 38, 'Dùng switch...case', 0),
(152, 38, 'Dùng alert()', 0),
(153, 39, 'Đối tượng trong JS', 0),
(154, 39, 'Cách gọi API trong JS', 0),
(155, 39, 'Khả năng truy cập biến của hàm con', 1),
(156, 39, 'Thuộc tính của CSS', 0),
(157, 40, 'Gọi API RESTful', 0),
(158, 40, 'Tạo vòng lặp for nhanh hơn', 0),
(159, 40, 'Tối ưu mạng', 0),
(160, 40, 'Cú pháp gọn hơn cho function', 1),
(161, 41, 'Chia bố cục trang', 0),
(162, 41, 'Sự kiện lan từ phần tử con đến cha', 1),
(163, 41, 'Tạo hiệu ứng loading', 0),
(164, 41, 'Gọi lại hàm liên tục', 0),
(165, 42, 'Đồng bộ dữ liệu', 0),
(166, 42, 'Đặt hàm chạy sau một khoảng thời gian', 1),
(167, 42, 'Tạo bảng HTML', 0),
(168, 42, 'Ẩn hiện nội dung', 0),
(169, 43, 'Một kiểu vòng lặp mới', 0),
(170, 43, 'Tạo CSS động', 0),
(171, 43, 'Thư viện vẽ biểu đồ', 0),
(172, 43, 'Định dạng dữ liệu dạng text dễ truyền qua mạng', 1),
(173, 44, 'Dùng event.preventDefault()', 1),
(174, 44, 'Dùng JSON.stringify()', 0),
(175, 44, 'Dùng setTimeout()', 0),
(176, 44, 'Dùng innerHTML', 0),
(177, 45, 'Gửi dữ liệu qua server', 0),
(178, 45, 'Lưu dữ liệu vào trình duyệt lâu dài', 1),
(179, 45, 'Tạo bảng HTML', 0),
(180, 45, 'Xử lý form', 0),
(181, 46, 'Thư viện cho PHP', 0),
(182, 46, 'Framework backend', 0),
(183, 46, 'Thư viện JavaScript để xây dựng giao diện người dùng', 1),
(184, 46, 'Trình duyệt mới', 0),
(185, 47, 'Thay thế HTML', 0),
(186, 47, 'Không hỗ trợ component', 0),
(187, 47, 'Dễ học, nhẹ, hỗ trợ reactive tốt', 1),
(188, 47, 'Chạy trên backend', 0),
(189, 48, 'Angular không hỗ trợ routing', 0),
(190, 48, 'React hỗ trợ template engine', 0),
(191, 48, 'Angular là framework, React là thư viện', 1),
(192, 48, 'React không dùng HTML', 0),
(193, 49, 'Lưu trữ dữ liệu trên server', 0),
(194, 49, 'Xử lý form HTML', 0),
(195, 49, 'Tạo CSS động', 0),
(196, 49, 'Quản lý state trong ứng dụng React', 1),
(197, 50, 'Tạo hiệu ứng CSS', 0),
(198, 50, 'Thay đổi màu nền trang', 0),
(199, 50, 'Gọi API RESTful', 0),
(200, 50, 'Tạo và tái sử dụng các khối UI nhỏ', 1),
(201, 51, 'Tạo hiệu ứng động', 0),
(202, 51, 'Tối ưu CSS', 0),
(203, 51, 'Quản lý form', 0),
(204, 51, 'Quản lý định tuyến trong ứng dụng Vue', 1),
(205, 52, 'Sử dụng Services hoặc NgRx', 1),
(206, 52, 'Dùng Vuex', 0),
(207, 52, 'Sử dụng Redux Toolkit', 0),
(208, 52, 'Dùng useState', 0),
(209, 53, 'Hàm cho phép dùng state và lifecycle trong function component', 1),
(210, 53, 'Công cụ test React', 0),
(211, 53, 'Thư viện hoạt ảnh', 0),
(212, 53, 'Bộ định tuyến cho React', 0),
(213, 54, 'Xử lý sự kiện chuột', 0),
(214, 54, 'Quản lý state toàn cục', 0),
(215, 54, 'Truyền dữ liệu từ component cha sang con', 1),
(216, 54, 'Kết nối API', 0),
(217, 55, 'Tạo route', 0),
(218, 55, 'Quản lý trạng thái tập trung', 1),
(219, 55, 'Xử lý DOM', 0),
(220, 55, 'Tạo biểu mẫu', 0),
(221, 56, 'Sử dụng template engine', 0),
(222, 56, 'Gán giá trị trực tiếp vào HTML', 0),
(223, 56, 'Sử dụng state và sự kiện onChange', 1),
(224, 56, 'Dùng thẻ form truyền thống', 0),
(225, 57, 'Tạo hình ảnh động', 0),
(226, 57, 'Là thư viện CSS', 0),
(227, 57, 'Hỗ trợ SSR và tối ưu SEO', 1),
(228, 57, 'Chỉ chạy backend', 0),
(229, 58, 'Dùng axios trong lifecycle hooks', 1),
(230, 58, 'Tạo thẻ iframe', 0),
(231, 58, 'Gán vào template', 0),
(232, 58, 'Dùng useEffect', 0),
(233, 59, 'Svelte compile ở build-time, React runtime', 1),
(234, 59, 'Svelte dùng JSX', 0),
(235, 59, 'Không có style riêng', 0),
(236, 59, 'Svelte không hỗ trợ component', 0),
(237, 60, 'Tăng CSS', 0),
(238, 60, 'Gộp JS vào file HTML', 0),
(239, 60, 'Dùng memo, lazy load, PureComponent', 1),
(240, 60, 'Tạo thêm DOM', 0),
(241, 61, 'Dùng media queries và layout linh hoạt', 1),
(242, 61, 'Ẩn header', 0),
(243, 61, 'Tăng padding', 0),
(244, 61, 'Thay màu giao diện', 0),
(245, 62, 'Framework CSS hỗ trợ responsive và component UI', 1),
(246, 62, 'Ngôn ngữ lập trình', 0),
(247, 62, 'Thư viện ảnh', 0),
(248, 62, 'Công cụ vẽ', 0),
(249, 63, 'Dùng class media', 0),
(250, 63, 'Ẩn bằng display: none', 0),
(251, 63, 'Dùng @media với điều kiện kích thước màn hình', 1),
(252, 63, 'Dùng JS để resize', 0),
(253, 64, 'Giá trị CSS ngẫu nhiên', 0),
(254, 64, 'Ngưỡng thay đổi giao diện theo độ rộng màn hình', 1),
(255, 64, 'Chức năng định dạng ảnh', 0),
(256, 64, 'Vị trí cố định trong file', 0),
(257, 65, 'Ẩn phần lớn nội dung', 0),
(258, 65, 'Tạo trang desktop trước', 0),
(259, 65, 'Dùng layout linh hoạt, ưu tiên UX', 1),
(260, 65, 'Giảm độ phân giải ảnh', 0),
(261, 66, 'Không hỗ trợ responsive', 0),
(262, 66, 'Tiện lợi, tùy biến cao, viết ít CSS', 1),
(263, 66, 'Không cần class', 0),
(264, 66, 'Tự động thêm style', 0),
(265, 67, 'Tạo màu nền', 0),
(266, 67, 'Dùng để định vị phần tử', 0),
(267, 67, 'Đơn vị tương đối giúp co giãn theo font', 1),
(268, 67, 'Chỉ dùng cho hình ảnh', 0),
(269, 68, 'Vùng hiển thị nội dung trên thiết bị', 1),
(270, 68, 'Chế độ ảnh nền', 0),
(271, 68, 'Kích thước file CSS', 0),
(272, 68, 'Độ dài ký tự', 0),
(273, 69, 'Dùng định dạng nhẹ như WebP, lazy load', 1),
(274, 69, 'Gộp ảnh vào JS', 0),
(275, 69, 'Chuyển ảnh sang PDF', 0),
(276, 69, 'Tăng độ phân giải', 0),
(277, 70, 'Ẩn nội dung khỏi máy tìm kiếm', 0),
(278, 70, 'Thiết kế giúp mọi người, kể cả khuyết tật, truy cập dễ dàng', 1),
(279, 70, 'Chỉ hỗ trợ mobile', 0),
(280, 70, 'Tăng tốc độ trang', 0),
(281, 71, 'Dùng thuộc tính aria-* để mô tả cho công cụ hỗ trợ', 1),
(282, 71, 'Chèn vào CSS', 0),
(283, 71, 'Ẩn thẻ HTML', 0),
(284, 71, 'Tạo ảnh minh họa', 0),
(285, 72, 'Dùng ít màu, nội dung rõ ràng, dễ nhìn', 1),
(286, 72, 'Ẩn toàn bộ footer', 0),
(287, 72, 'Tăng font và màu nền đậm', 0),
(288, 72, 'Dùng hiệu ứng liên tục', 0),
(289, 73, 'UX dùng cho app, UI dùng cho web', 0),
(290, 73, 'UX là trải nghiệm, UI là giao diện người dùng', 1),
(291, 73, 'UX là frontend, UI là backend', 0),
(292, 73, 'UX là màu sắc, UI là bố cục', 0),
(293, 74, 'Tạo bảng dữ liệu', 0),
(294, 74, 'Phác thảo bố cục giao diện trước khi làm thật', 1),
(295, 74, 'Viết nội dung SEO', 0),
(296, 74, 'Lưu trữ thông tin người dùng', 0),
(297, 75, 'Git, SSH, Linux', 0),
(298, 75, 'Word, Excel, PowerPoint', 0),
(299, 75, 'Figma, Adobe XD, Sketch', 1),
(300, 75, 'Photoshop Filter', 0),
(301, 76, 'Công cụ kiểm thử phần mềm', 0),
(302, 76, 'Một dạng sơ đồ mạng', 0),
(303, 76, 'Một kiểu dữ liệu trong C++', 0),
(304, 76, 'Hệ thống lưu trữ dữ liệu dạng bảng có quan hệ với nhau', 1),
(305, 77, 'Xác định duy nhất mỗi dòng trong bảng', 1),
(306, 77, 'Gán quyền truy cập', 0),
(307, 77, 'Lưu trữ dữ liệu hình ảnh', 0),
(308, 77, 'Liên kết giữa 2 cơ sở dữ liệu', 0),
(309, 78, 'Mật khẩu của hệ thống', 0),
(310, 78, 'Khóa mã hóa dữ liệu', 0),
(311, 78, 'Khóa dùng để liên kết các bảng', 1),
(312, 78, 'Tham số nhập đầu vào', 0),
(313, 79, 'Chương trình dịch ngôn ngữ C', 0),
(314, 79, 'Hệ quản trị cơ sở dữ liệu quan hệ', 1),
(315, 79, 'Một dạng phần mềm kế toán', 0),
(316, 79, 'Định dạng file văn bản', 0),
(317, 80, 'Chrome, Firefox, Edge', 0),
(318, 80, 'Windows, Linux, MacOS', 0),
(319, 80, 'Notepad, Word, Excel', 0),
(320, 80, 'MySQL, PostgreSQL, SQL Server', 1),
(321, 81, 'Kiểu dữ liệu mạng trong HTML', 0),
(322, 81, 'CSDL chỉ lưu ảnh', 0),
(323, 81, 'CSDL dùng cho AI', 0),
(324, 81, 'CSDL tổ chức dữ liệu theo cây', 1),
(325, 82, 'Giao thức truyền dữ liệu', 0),
(326, 82, 'CSDL không có cấu trúc', 0),
(327, 82, 'Hệ điều hành máy chủ', 0),
(328, 82, 'CSDL với nhiều liên kết phức tạp', 1),
(329, 83, 'Khó bảo trì', 0),
(330, 83, 'Bảo mật thấp, khó mở rộng', 0),
(331, 83, 'Chỉ lưu trữ được văn bản', 0),
(332, 83, 'Tính toàn vẹn, dễ truy vấn, linh hoạt', 1),
(333, 84, 'Khó mở rộng với dữ liệu phi cấu trúc', 1),
(334, 84, 'Không thể cập nhật dữ liệu', 0),
(335, 84, 'Không có giao diện', 0),
(336, 84, 'Không dùng được trên mobile', 0),
(337, 85, 'Lưu vào RAM', 0),
(338, 85, 'Lưu vào file Word', 0),
(339, 85, 'Lưu trữ dữ liệu trong bảng có hàng và cột', 1),
(340, 85, 'Lưu dưới dạng ảnh', 0),
(341, 86, 'Một ngôn ngữ lập trình', 0),
(342, 86, 'Kiểu dữ liệu trong SQL', 0),
(343, 86, 'Cấu trúc tổ chức của toàn bộ cơ sở dữ liệu', 1),
(344, 86, 'Chức năng bảo mật dữ liệu', 0),
(345, 87, 'Chạy trình duyệt', 0),
(346, 87, 'Thiết kế giao diện người dùng', 0),
(347, 87, 'Lưu trữ và truy xuất dữ liệu', 1),
(348, 87, 'Gửi email', 0),
(349, 88, 'Không lưu trữ được', 0),
(350, 88, 'Dữ liệu chưa phân loại', 0),
(351, 88, 'Dữ liệu có cấu trúc rõ ràng như bảng', 1),
(352, 88, 'Dữ liệu từ ảnh và video', 0),
(353, 89, 'Dữ liệu của máy chủ', 0),
(354, 89, 'Dữ liệu chỉ đọc', 0),
(355, 89, 'Dữ liệu không theo cấu trúc bảng như ảnh, video', 1),
(356, 89, 'Một loại mạng không dây', 0),
(357, 90, 'Cài đặt phần mềm', 0),
(358, 90, 'Vẽ sơ đồ', 0),
(359, 90, 'Thiết kế website', 0),
(360, 90, 'Giúp lưu trữ, truy vấn và phân tích dữ liệu hiệu quả', 1),
(361, 91, 'Tạo giao diện người dùng', 0),
(362, 91, 'Bảo vệ phần mềm khỏi virus', 0),
(363, 91, 'Quá trình tổ chức lại dữ liệu để giảm dư thừa', 1),
(364, 91, 'Tối ưu tốc độ mạng', 0),
(365, 92, 'Giảm dung lượng ổ cứng', 0),
(366, 92, 'Không có thuộc tính lặp trong bảng', 1),
(367, 92, 'Tăng tốc độ mạng', 0),
(368, 92, 'Tối ưu các truy vấn', 0),
(369, 93, 'Không có ràng buộc', 0),
(370, 93, 'Phụ thuộc vào khóa ngoại', 0),
(371, 93, 'Phụ thuộc đầy đủ vào khóa chính', 1),
(372, 93, 'Không cần khóa chính', 0),
(373, 94, 'Tạo nhiều bảng hơn', 0),
(374, 94, 'Không phụ thuộc bắc cầu vào khóa chính', 1),
(375, 94, 'Chỉ dùng cho bảng nhỏ', 0),
(376, 94, 'Không có quan hệ giữa các bảng', 0),
(377, 95, 'Lỗi trong hệ điều hành', 0),
(378, 95, 'Một công cụ lập trình', 0),
(379, 95, 'Cơ sở dữ liệu không quan hệ', 0),
(380, 95, 'Biến thể nâng cao của 3NF', 1),
(381, 96, 'Lưu dữ liệu vào cache', 0),
(382, 96, 'Khó truy cập dữ liệu', 0),
(383, 96, 'Giảm dư thừa, tăng tính toàn vẹn', 1),
(384, 96, 'Tạo ra nhiều lỗi hơn', 0),
(385, 97, 'Dễ bị virus', 0),
(386, 97, 'Không có chỉ mục', 0),
(387, 97, 'Truy vấn chậm, khó bảo trì', 1),
(388, 97, 'Không thể sao lưu', 0),
(389, 98, 'Kiểm thử phần mềm', 0),
(390, 98, 'Tạo giao diện người dùng', 0),
(391, 98, 'Vẽ sơ đồ thực thể - liên kết giữa bảng', 1),
(392, 98, 'Viết mã CSS', 0),
(393, 99, 'Địa chỉ IP', 0),
(394, 99, 'Trình duyệt web', 0),
(395, 99, 'Câu lệnh SQL', 0),
(396, 99, 'Đối tượng chính cần quản lý trong hệ thống', 1),
(397, 100, 'Mối quan hệ giữa các thực thể trong ERD', 1),
(398, 100, 'Dữ liệu dạng chuỗi', 0),
(399, 100, 'Thẻ HTML', 0),
(400, 100, 'Thuộc tính CSS', 0),
(401, 126, 'Quản lý truy cập đồng thời để tránh xung đột', 1),
(402, 126, 'Tối ưu hóa truy vấn', 0),
(403, 126, 'Mã hóa thông tin người dùng', 0),
(404, 126, 'Sao lưu dữ liệu', 0),
(405, 127, 'Mất dữ liệu khi restore', 0),
(406, 127, 'Xung đột dữ liệu khi insert', 0),
(407, 127, 'Lỗi do thiếu quyền', 0),
(408, 127, 'Hai tiến trình chờ nhau vô thời hạn', 1),
(409, 128, 'Mật khẩu hệ thống', 0),
(410, 128, 'Định nghĩa bảng', 0),
(411, 128, 'Tạo trigger tự động', 0),
(412, 128, 'Cơ chế khóa tài nguyên để tránh xung đột', 1),
(413, 129, 'Cho phép thay đổi schema', 0),
(414, 129, 'Chạy song song không cần kiểm soát', 0),
(415, 129, 'Dữ liệu được mã hóa riêng', 0),
(416, 129, 'Các giao dịch tách biệt và không ảnh hưởng lẫn nhau', 1),
(417, 130, 'Ẩn field nhập liệu', 0),
(418, 130, 'Dùng alert để cảnh báo', 0),
(419, 130, 'Dùng prepared statements hoặc ORM', 1),
(420, 130, 'Tắt JavaScript', 0),
(421, 131, 'Cập nhật phần mềm', 0),
(422, 131, 'Xóa toàn bộ dữ liệu', 0),
(423, 131, 'Sao lưu dữ liệu để khôi phục khi gặp sự cố', 1),
(424, 131, 'Khóa người dùng truy cập', 0),
(425, 132, 'Tối ưu mạng', 0),
(426, 132, 'Thêm user mới', 0),
(427, 132, 'Tạo trigger mới', 0),
(428, 132, 'Khôi phục dữ liệu từ bản sao lưu', 1),
(429, 133, 'Dùng thuật toán mã hóa như AES', 1),
(430, 133, 'Giảm kích thước bảng', 0),
(431, 133, 'Lưu vào file Excel', 0),
(432, 133, 'Gắn watermark', 0),
(433, 134, 'Tập hợp quyền gán cho người dùng', 1),
(434, 134, 'Kiểu dữ liệu mới', 0),
(435, 134, 'Lỗi hệ thống', 0),
(436, 134, 'Tên khóa chính', 0),
(437, 135, 'Xoá user cũ', 0),
(438, 135, 'Dùng lệnh DROP', 0),
(439, 135, 'Dùng SQL GRANT và REVOKE', 1),
(440, 135, 'Tạo view mới', 0),
(441, 136, 'Chống lỗi phần mềm', 0),
(442, 136, 'Tạo giao diện mới', 0),
(443, 136, 'Tăng tốc độ truy vấn dữ liệu', 1),
(444, 136, 'Giảm dung lượng lưu trữ', 0),
(445, 137, 'SELECT * FROM table', 0),
(446, 137, 'INSERT INTO bảng rỗng', 0),
(447, 137, 'SELECT cột cụ thể với điều kiện hợp lý', 1),
(448, 137, 'DELETE FROM table WHERE id > 0', 0),
(449, 138, 'Index sắp xếp vật lý dữ liệu trong bảng', 1),
(450, 138, 'Index không có khóa chính', 0),
(451, 138, 'Index lưu ngoài bảng', 0),
(452, 138, 'Index dùng cho kiểu ảnh', 0),
(453, 139, 'Index chỉ dùng cho khóa chính', 0),
(454, 139, 'Index tách biệt khỏi dữ liệu bảng', 1),
(455, 139, 'Index trong RAM', 0),
(456, 139, 'Index chứa toàn bộ bảng', 0),
(457, 140, 'Không có hiệu quả gì', 0),
(458, 140, 'Tăng kích thước lưu trữ, làm chậm khi insert/update', 1),
(459, 140, 'Không thể backup dữ liệu', 0),
(460, 140, 'Giảm tốc độ mạng', 0),
(461, 141, 'Chỉ chọn các cột cần thiết, có điều kiện cụ thể', 1),
(462, 141, 'Không dùng index', 0),
(463, 141, 'Dùng SELECT * FROM', 0),
(464, 141, 'Dùng nhiều subquery', 0),
(465, 142, 'Cách tạo user mới', 0),
(466, 142, 'Công cụ phân tích cách thực hiện truy vấn SQL', 1),
(467, 142, 'Tạo bảng mới', 0),
(468, 142, 'Lệnh cập nhật dữ liệu', 0),
(469, 143, 'Tắt index', 0),
(470, 143, 'Bỏ qua điều kiện WHERE', 0),
(471, 143, 'Thay đổi định dạng dữ liệu', 0),
(472, 143, 'Chỉ dẫn cho trình tối ưu hóa SQL chọn chiến lược tốt hơn', 1),
(473, 144, 'Tăng RAM server', 0),
(474, 144, 'Tách bảng chính', 0),
(475, 144, 'Dùng nhiều bảng phụ', 0),
(476, 144, 'Chọn đúng loại join, thêm chỉ mục phù hợp', 1),
(477, 145, 'Index chứa đủ thông tin để trả kết quả mà không cần đọc bảng', 1),
(478, 145, 'Chỉ chứa dữ liệu số', 0),
(479, 145, 'Index cho bảng có cột bị lặp', 0),
(480, 145, 'Chỉ dùng trong NoSQL', 0),
(481, 146, 'Dùng chỉ mục và điều kiện truy vấn hợp lý', 1),
(482, 146, 'Tắt SQL', 0),
(483, 146, 'Xoá dữ liệu', 0),
(484, 146, 'Tạo bảng mới', 0),
(485, 147, 'Kế hoạch học tập của sinh viên', 0),
(486, 147, 'Tài liệu quản trị', 0),
(487, 147, 'Sơ đồ mạng', 0),
(488, 147, 'Chi tiết cách SQL thực hiện truy vấn', 1),
(489, 148, 'Dùng JSON', 0),
(490, 148, 'Chạy lại nhiều lần', 0),
(491, 148, 'Dùng công cụ như EXPLAIN, theo dõi thời gian và tài nguyên', 1),
(492, 148, 'Tắt index', 0),
(493, 149, 'Mức độ phân mảnh làm giảm hiệu năng index', 1),
(494, 149, 'Tập tin bị virus', 0),
(495, 149, 'Kích thước index tăng', 0),
(496, 149, 'Bảng bị lỗi', 0),
(497, 150, 'Xóa toàn bộ index', 0),
(498, 150, 'Sao lưu dữ liệu', 0),
(499, 150, 'Xóa bảng dữ liệu', 0),
(500, 150, 'Dùng lệnh REBUILD INDEX để tái cấu trúc', 1),
(501, 151, 'NoSQL linh hoạt hơn và phù hợp với dữ liệu phi cấu trúc', 1),
(502, 151, 'NoSQL chỉ hỗ trợ bảng', 0),
(503, 151, 'NoSQL chặt chẽ hơn SQL', 0),
(504, 151, 'SQL không dùng index', 0),
(505, 152, 'CSDL hình ảnh', 0),
(506, 152, 'CSDL dạng bảng', 0),
(507, 152, 'CSDL phân cấp', 0),
(508, 152, 'CSDL dạng tài liệu (document)', 1),
(509, 153, 'CSDL lưu dữ liệu dạng bảng', 0),
(510, 153, 'CSDL lưu trữ dữ liệu dạng tài liệu như JSON', 1),
(511, 153, 'CSDL mạng LAN', 0),
(512, 153, 'CSDL cho máy ảo', 0),
(513, 154, 'CSDL cho điện thoại', 0),
(514, 154, 'Dùng cho API', 0),
(515, 154, 'CSDL lưu dữ liệu theo cột', 1),
(516, 154, 'CSDL lưu dữ liệu theo hàng', 0),
(517, 155, 'Chỉ lưu ảnh', 0),
(518, 155, 'Không hỗ trợ index', 0),
(519, 155, 'Dùng cho IoT', 0),
(520, 155, 'Lưu trữ và xử lý dữ liệu dạng nút và cạnh', 1),
(521, 156, 'Không truy vấn được', 0),
(522, 156, 'Linh hoạt, mở rộng tốt, xử lý dữ liệu phi cấu trúc', 1),
(523, 156, 'Không hỗ trợ dữ liệu lớn', 0),
(524, 156, 'Dùng riêng cho AI', 0),
(525, 157, 'Không hỗ trợ JSON', 0),
(526, 157, 'Không hỗ trợ nhiều người dùng', 0),
(527, 157, 'Không dùng cho dữ liệu lớn', 0),
(528, 157, 'Thiếu tính nhất quán, khó quản lý phức tạp', 1),
(529, 158, 'Lưu dạng document (JSON-like)', 1),
(530, 158, 'Lưu vào Excel', 0),
(531, 158, 'Lưu dạng bảng có khóa chính', 0),
(532, 158, 'Lưu qua RAM', 0),
(533, 159, 'Giao diện của MongoDB', 0),
(534, 159, 'Một framework', 0),
(535, 159, 'Cách đặt tên field', 0),
(536, 159, 'Định dạng nhị phân mở rộng của JSON trong MongoDB', 1),
(537, 160, 'Dùng qua PHPMyAdmin', 0),
(538, 160, 'Dùng SQL như MySQL', 0),
(539, 160, 'Dùng câu lệnh PL/SQL', 0),
(540, 160, 'Dùng các lệnh dạng JSON như find(), insertOne()', 1),
(541, 101, 'Thứ tự cột', 0),
(542, 101, 'Kiểu dữ liệu trong bảng', 0),
(543, 101, 'Độ dài chuỗi', 0),
(544, 101, 'Số lượng mối quan hệ giữa các thực thể', 1),
(545, 102, 'Vẽ lại bằng tay', 0),
(546, 102, 'Sử dụng CSS', 0),
(547, 102, 'Chuyển đổi thành mã Java', 0),
(548, 102, 'Xác định thực thể và mối quan hệ để tạo bảng', 1),
(549, 103, 'Lệnh điều kiện', 0),
(550, 103, 'Tên bảng trong SQL', 0),
(551, 103, 'Thông tin mô tả thực thể', 1),
(552, 103, 'Dòng dữ liệu', 0),
(553, 104, 'Bảng không có khóa chính', 0),
(554, 104, 'Thực thể phụ thuộc thực thể khác', 1),
(555, 104, 'Bảng tạm', 0),
(556, 104, 'Khóa ngoại', 0),
(557, 105, 'Python', 0),
(558, 105, 'Word, Excel', 0),
(559, 105, 'MySQL Workbench, Draw.io', 1),
(560, 105, 'Photoshop', 0),
(561, 106, 'Chèn dữ liệu', 0),
(562, 106, 'Tạo bảng', 0),
(563, 106, 'Xóa dữ liệu', 0),
(564, 106, 'Truy xuất dữ liệu từ bảng', 1),
(565, 107, 'Chia bảng thành nhiều phần', 0),
(566, 107, 'Kết hợp dữ liệu từ nhiều bảng dựa trên khóa chung', 1),
(567, 107, 'Ẩn cột trong bảng', 0),
(568, 107, 'Tạo dữ liệu ngẫu nhiên', 0),
(569, 108, 'Tạo bảng mới', 0),
(570, 108, 'Tự động thực hiện hành động khi có sự kiện', 1),
(571, 108, 'Thay đổi kiểu dữ liệu', 0),
(572, 108, 'Tạo view', 0),
(573, 109, 'Thêm dữ liệu mới vào bảng', 1),
(574, 109, 'Sửa cấu trúc bảng', 0),
(575, 109, 'Xóa bảng', 0),
(576, 109, 'Tạo chỉ mục', 0),
(577, 110, 'Tạo bảng', 0),
(578, 110, 'Thêm cột mới', 0),
(579, 110, 'Cập nhật dữ liệu hiện có', 1),
(580, 110, 'Xóa bảng', 0),
(581, 111, 'Tạo chỉ mục', 0),
(582, 111, 'Xóa dữ liệu khỏi bảng', 1),
(583, 111, 'Gộp bảng', 0),
(584, 111, 'Tạo trigger', 0),
(585, 112, 'INNER JOIN chỉ lấy bản ghi khớp, LEFT JOIN lấy tất cả bản ghi bên trái', 1),
(586, 112, 'Không có sự khác biệt', 0),
(587, 112, 'LEFT JOIN lấy bản ghi bên phải', 0),
(588, 112, 'INNER JOIN là duy nhất', 0),
(589, 113, 'Tạo bảng phụ', 0),
(590, 113, 'Nhóm dữ liệu theo một hoặc nhiều cột', 1),
(591, 113, 'Sắp xếp dữ liệu', 0),
(592, 113, 'Xóa dữ liệu', 0),
(593, 114, 'Tìm giá trị lớn nhất', 0),
(594, 114, 'Tính giá trị trung bình', 0),
(595, 114, 'Tính tổng giá trị', 0),
(596, 114, 'Đếm số lượng bản ghi', 1),
(597, 115, 'Gộp chuỗi ký tự', 0),
(598, 115, 'Đếm bản ghi', 0),
(599, 115, 'Tính độ dài chuỗi', 0),
(600, 115, 'Tính tổng giá trị', 1),
(601, 116, 'Truy vấn lồng trong truy vấn khác', 1),
(602, 116, 'Câu lệnh SELECT đơn giản', 0),
(603, 116, 'Lệnh xóa bảng', 0),
(604, 116, 'Tạo view', 0),
(605, 117, 'Thay thế WHERE', 0),
(606, 117, 'Lọc dữ liệu sau GROUP BY', 1),
(607, 117, 'Dùng trong JOIN', 0),
(608, 117, 'Tạo trigger', 0),
(609, 118, 'Xóa dòng', 0),
(610, 118, 'Tạo bảng', 0),
(611, 118, 'Gộp dữ liệu', 0),
(612, 118, 'Sắp xếp dữ liệu theo thứ tự', 1),
(613, 119, 'Tập hợp câu lệnh SQL lưu trữ sẵn để thực thi', 1),
(614, 119, 'View', 0),
(615, 119, 'Từ khóa đặc biệt', 0),
(616, 119, 'Khóa chính', 0),
(617, 120, 'Tạo bảng mới', 0),
(618, 120, 'Loại bỏ giá trị trùng lặp', 1),
(619, 120, 'Tính tổng', 0),
(620, 120, 'Chia bảng', 0),
(621, 121, 'Một đơn vị công việc có thể được commit hoặc rollback', 1),
(622, 121, 'Truy vấn bảng đơn giản', 0),
(623, 121, 'Chỉnh sửa view', 0),
(624, 121, 'Tạo bảng tạm', 0),
(625, 122, 'Tập hợp 4 thuộc tính đảm bảo độ tin cậy của transaction', 1),
(626, 122, 'Công cụ backup', 0),
(627, 122, 'Ngôn ngữ lập trình', 0),
(628, 122, 'Một kiểu dữ liệu', 0),
(629, 123, 'Hủy thao tác', 0),
(630, 123, 'Xóa toàn bộ dữ liệu', 0),
(631, 123, 'Xác nhận và lưu thay đổi', 1),
(632, 123, 'Tạo bảng mới', 0),
(633, 124, 'Tạo trigger', 0),
(634, 124, 'Tạo bảng tạm', 0),
(635, 124, 'Lưu thay đổi', 0),
(636, 124, 'Hoàn tác thao tác chưa commit', 1),
(637, 125, 'Xóa dữ liệu thường xuyên', 0),
(638, 125, 'Dùng khóa chính, khóa ngoại, và ràng buộc', 1),
(639, 125, 'Dùng CSS để bảo vệ', 0),
(640, 125, 'Tắt kiểm tra lỗi', 0),
(641, 181, 'Khả năng máy móc mô phỏng trí tuệ con người', 1),
(642, 181, 'Một ngôn ngữ lập trình', 0),
(643, 181, 'Thiết bị điện tử', 0),
(644, 181, 'Một hệ điều hành', 0),
(645, 182, 'AI là một phần của DL', 0),
(646, 182, 'DL dùng để vẽ đồ họa', 0),
(647, 182, 'AI là tổng quát, ML là học từ dữ liệu, DL là ML nâng cao', 1),
(648, 182, 'ML điều khiển AI và DL là phần mềm', 0),
(649, 183, 'Xe tự lái, trợ lý ảo, chẩn đoán y khoa', 1),
(650, 183, 'Chạy quảng cáo thủ công', 0),
(651, 183, 'Chơi game online', 0),
(652, 183, 'Thiết kế nội thất', 0),
(653, 184, 'AI mạnh có tư duy như con người, AI yếu làm nhiệm vụ cụ thể', 1),
(654, 184, 'AI yếu nhanh hơn', 0),
(655, 184, 'AI mạnh dễ cài đặt hơn', 0),
(656, 184, 'AI mạnh dùng trong game, AI yếu dùng trong thực tế', 0),
(657, 185, 'Do Facebook phát triển', 0),
(658, 185, 'Bắt đầu từ những năm 1950 với Alan Turing', 1),
(659, 185, 'Xuất phát từ ngành y học', 0),
(660, 185, 'Bắt đầu năm 2000', 0),
(661, 186, 'Công cụ lập trình', 0),
(662, 186, 'Bài kiểm tra IQ', 0),
(663, 186, 'Một loại thuật toán', 0),
(664, 186, 'Kiểm tra khả năng máy bắt chước con người', 1),
(665, 187, 'Chỉ trong mạng xã hội', 0),
(666, 187, 'Chỉ trong âm nhạc', 0),
(667, 187, 'Chỉ trong game', 0),
(668, 187, 'Y tế, giao thông, tài chính, giáo dục', 1),
(669, 188, 'Một công cụ AI', 0),
(670, 188, 'Một thuật toán học máy', 0),
(671, 188, 'Đạo đức và trách nhiệm khi sử dụng AI', 1),
(672, 188, 'Cách lưu trữ dữ liệu', 0),
(673, 189, 'AI không thể làm gì', 0),
(674, 189, 'AI chỉ dùng cho giải trí', 0),
(675, 189, 'AI đã thay thế hoàn toàn', 0),
(676, 189, 'Một số công việc có thể bị thay thế, nhưng không hoàn toàn', 1),
(677, 190, 'Không cần dữ liệu', 0),
(678, 190, 'Chỉ cần hình ảnh đẹp', 0),
(679, 190, 'File word và excel', 0),
(680, 190, 'Dữ liệu lớn, đa dạng và chất lượng cao', 1),
(681, 191, 'Thiết bị nghe nhạc', 0),
(682, 191, 'Ứng dụng trò chơi', 0),
(683, 191, 'Một phần cứng AI', 0),
(684, 191, 'Máy học từ dữ liệu để đưa ra dự đoán', 1),
(685, 192, 'Thiết kế đồ họa', 0),
(686, 192, 'Nhánh của ML sử dụng mạng nơ-ron sâu', 1),
(687, 192, 'Phần mềm dựng phim', 0),
(688, 192, 'Trình duyệt web', 0),
(689, 193, 'Frontend, Backend, Database', 0),
(690, 193, 'ML, DL, NLP, Robotics, Expert System', 1),
(691, 193, 'Hệ điều hành, CPU, RAM', 0),
(692, 193, 'Photoshop, Illustrator, Canva', 0),
(693, 194, 'Qua mô hình học máy được huấn luyện', 1),
(694, 194, 'Chỉ dùng lý thuyết', 0),
(695, 194, 'Tạo ngẫu nhiên', 0),
(696, 194, 'Tự suy luận mà không cần dữ liệu', 0),
(697, 195, 'Dùng để thiết kế logo', 0),
(698, 195, 'Cung cấp nguồn dữ liệu lớn để huấn luyện AI', 1),
(699, 195, 'Chỉ lưu trữ hình ảnh', 0),
(700, 195, 'Không liên quan', 0),
(701, 196, 'Học từ dữ liệu chưa có nhãn', 0),
(702, 196, 'Học từ dữ liệu gán nhãn trước', 1),
(703, 196, 'Tự học không cần dữ liệu', 0),
(704, 196, 'Chỉ học khi có mạng', 0),
(705, 197, 'Học từ dữ liệu chưa gán nhãn', 1),
(706, 197, 'Học qua mạng xã hội', 0),
(707, 197, 'Dạy bằng tay', 0),
(708, 197, 'Tải xuống kiến thức', 0),
(709, 198, 'Chỉ học lý thuyết', 0),
(710, 198, 'Làm bài kiểm tra định kỳ', 0),
(711, 198, 'Gán nhãn dữ liệu', 0),
(712, 198, 'Học qua tương tác và phần thưởng/phạt', 1),
(713, 199, 'Không thuộc AI', 0),
(714, 199, 'Học tăng cường', 0),
(715, 199, 'Học không giám sát', 0),
(716, 199, 'Học có giám sát', 1),
(717, 200, 'Ẩn thông tin', 0),
(718, 200, 'Tạo ảnh mới', 0),
(719, 200, 'Tạo bảng thống kê', 0),
(720, 200, 'Dự đoán nhãn hoặc nhóm cho dữ liệu mới', 1),
(721, 161, 'Redis chỉ dùng cho dữ liệu dạng bảng', 0),
(722, 161, 'Redis là hệ quản trị cơ sở dữ liệu quan hệ', 0),
(723, 161, 'Redis không hỗ trợ lưu trữ dữ liệu', 0),
(724, 161, 'Redis là cơ sở dữ liệu key-value lưu trữ trên RAM', 1),
(725, 162, 'Dữ liệu được truy xuất thông qua khóa', 1),
(726, 162, 'Key-value store lưu trữ dạng quan hệ', 0),
(727, 162, 'Key-value store dùng để tính toán số học', 0),
(728, 162, 'Dữ liệu phải truy vấn bằng JOIN', 0),
(729, 163, 'Ứng dụng cần mở rộng nhanh và linh hoạt', 1),
(730, 163, 'Ứng dụng xử lý giao dịch tài chính phức tạp', 0),
(731, 163, 'Ứng dụng không có nhu cầu lưu trữ dữ liệu', 0),
(732, 163, 'Ứng dụng chỉ lưu dữ liệu nhỏ cố định', 0),
(733, 164, 'Sharding là kiểm tra tính toàn vẹn', 0),
(734, 164, 'Sharding là sao lưu dữ liệu', 0),
(735, 164, 'Sharding là phân mảnh dữ liệu theo khóa', 1),
(736, 164, 'Sharding là dạng mã hoá dữ liệu', 0),
(737, 165, 'Replication là chia nhỏ dữ liệu', 0),
(738, 165, 'Replication là sao chép dữ liệu sang nút khác', 1),
(739, 165, 'Replication là mã hoá dữ liệu', 0),
(740, 165, 'Replication là phân loại dữ liệu', 0),
(741, 166, 'CAP là mô hình phát triển phần mềm', 0),
(742, 166, 'CAP là một thuật toán tìm kiếm', 0),
(743, 166, 'CAP viết tắt của Consistency, Availability, Partition tolerance', 1),
(744, 166, 'CAP chỉ áp dụng cho mạng nội bộ', 0),
(745, 167, 'BASE đảm bảo toàn vẹn dữ liệu như ACID', 0),
(746, 167, 'BASE là mô hình thay thế cho ACID trong NoSQL', 1),
(747, 167, 'BASE là một hệ quản trị dữ liệu', 0),
(748, 167, 'BASE dùng trong cơ sở dữ liệu quan hệ', 0),
(749, 168, 'MongoDB không hỗ trợ JSON', 0),
(750, 168, 'MongoDB lưu dữ liệu dạng bảng', 0),
(751, 168, 'MongoDB là cơ sở dữ liệu quan hệ', 0),
(752, 168, 'MongoDB là cơ sở dữ liệu dạng document', 1),
(753, 169, 'Cột chứa các bảng con', 0),
(754, 169, 'Cột trong cơ sở dữ liệu quan hệ tương ứng với field', 1),
(755, 169, 'Cột là một kiểu dữ liệu', 0),
(756, 169, 'Cột tương ứng với bảng', 0),
(757, 170, 'Document là kiểu dữ liệu quan hệ', 0),
(758, 170, 'Trong NoSQL, một document có thể có cấu trúc khác nhau', 1),
(759, 170, 'Document phải có cấu trúc giống hệt nhau', 0),
(760, 170, 'Document không chứa dữ liệu', 0),
(761, 171, 'Cassandra là ngôn ngữ lập trình', 0),
(762, 171, 'Cassandra là hệ quản trị quan hệ', 0),
(763, 171, 'Cassandra không lưu trữ dữ liệu phân tán', 0),
(764, 171, 'Cassandra là cơ sở dữ liệu dạng column-family', 1),
(765, 172, 'NoSQL phù hợp với Big Data', 1),
(766, 172, 'NoSQL chỉ dùng cho dữ liệu nhỏ', 0),
(767, 172, 'NoSQL bắt buộc phải có schema', 0),
(768, 172, 'NoSQL không dùng cho dữ liệu lớn', 0),
(769, 173, 'Data lake là bảng dữ liệu nhỏ', 0),
(770, 173, 'Data lake lưu trữ dữ liệu thô chưa xử lý', 1),
(771, 173, 'Data lake chỉ dùng trong SQL', 0),
(772, 173, 'Data lake chỉ lưu dữ liệu đã xử lý', 0),
(773, 174, 'Elasticsearch dùng để backup dữ liệu', 0),
(774, 174, 'Elasticsearch không hỗ trợ tìm kiếm full-text', 0),
(775, 174, 'Elasticsearch là cơ sở dữ liệu quan hệ', 0),
(776, 174, 'Elasticsearch là công cụ tìm kiếm văn bản mạnh', 1),
(777, 175, 'MapReduce dùng để mã hóa dữ liệu', 0),
(778, 175, 'MapReduce là mô hình xử lý dữ liệu song song', 1),
(779, 175, 'MapReduce chỉ chạy trên máy đơn', 0),
(780, 175, 'MapReduce là một cơ sở dữ liệu', 0),
(781, 176, 'Hadoop không hỗ trợ xử lý song song', 0),
(782, 176, 'Hadoop sử dụng HDFS để lưu trữ dữ liệu phân tán', 1),
(783, 176, 'Hadoop là công cụ trực quan hóa', 0),
(784, 176, 'Hadoop là cơ sở dữ liệu quan hệ', 0),
(785, 177, 'Data warehouse là bộ nhớ cache', 0),
(786, 177, 'Data warehouse chứa dữ liệu đã xử lý và tổ chức', 1),
(787, 177, 'Data warehouse lưu dữ liệu thô', 0),
(788, 177, 'Data warehouse dùng để thu thập dữ liệu trực tiếp từ thiết bị', 0),
(789, 178, 'MongoDB dùng XML làm định dạng mặc định', 0),
(790, 178, 'MongoDB sử dụng BSON để lưu trữ dữ liệu', 1),
(791, 178, 'MongoDB không hỗ trợ định dạng nhị phân', 0),
(792, 178, 'MongoDB chỉ hỗ trợ văn bản thuần túy', 0),
(793, 179, 'Graph database lưu trữ dạng bảng', 0),
(794, 179, 'Graph database chỉ dùng để tính toán số học', 0),
(795, 179, 'Graph database không hỗ trợ mối quan hệ', 0),
(796, 179, 'Graph database lưu trữ dữ liệu dạng nút và cạnh', 1),
(797, 180, 'Neo4j là hệ quản trị quan hệ', 0),
(798, 180, 'Neo4j là hệ điều hành', 0),
(799, 180, 'Neo4j là một graph database', 1),
(800, 180, 'Neo4j là phần mềm thiết kế đồ hoạ', 0),
(801, 201, 'Dữ liệu huấn luyện quá nhỏ', 0),
(802, 201, 'Thiếu xử lý dữ liệu', 0),
(803, 201, 'Không chia tập kiểm tra', 0),
(804, 201, 'Lỗi do mô hình quá phức tạp', 1),
(805, 202, 'Sử dụng quá nhiều đặc trưng', 0),
(806, 202, 'Dữ liệu quá phức tạp', 0),
(807, 202, 'Không có kiểm định chéo', 0),
(808, 202, 'Mô hình quá đơn giản', 1),
(809, 203, 'Sử dụng độ chính xác', 1),
(810, 203, 'Dựa vào cảm giác', 0),
(811, 203, 'Nhìn vào số dòng dữ liệu', 0),
(812, 203, 'Đếm số đặc trưng', 0),
(813, 204, 'Phương pháp huấn luyện', 0),
(814, 204, 'Thuật toán học máy', 0),
(815, 204, 'Kỹ thuật chia dữ liệu để kiểm thử', 1),
(816, 204, 'Công cụ visualization', 0),
(817, 205, 'Thuật toán thống kê', 0),
(818, 205, 'Kỹ thuật deep learning', 0),
(819, 205, 'Hệ thống rule-based', 0),
(820, 205, 'Thuật toán phân loại dạng cây', 1),
(821, 206, 'Cây đơn độc lập', 0),
(822, 206, 'Tập hợp nhiều cây quyết định', 1),
(823, 206, 'Một loại RNN', 0),
(824, 206, 'Giải thuật tìm kiếm', 0),
(825, 207, 'Thuật toán phân lớp tuyến tính', 1),
(826, 207, 'Thuật toán tìm luật', 0),
(827, 207, 'Một loại mạng nơ-ron', 0),
(828, 207, 'Hệ thống suy luận', 0),
(829, 208, 'Mạng học sâu', 0),
(830, 208, 'Thuật toán giám sát', 0),
(831, 208, 'Kỹ thuật làm sạch dữ liệu', 0),
(832, 208, 'Thuật toán phân cụm không giám sát', 1),
(833, 209, 'Tăng độ sâu mạng', 0),
(834, 209, 'Dùng PCA', 0),
(835, 209, 'Giảm số lượng đặc trưng', 0),
(836, 209, 'Dùng oversampling hoặc undersampling', 1),
(837, 210, 'Xóa cột dữ liệu', 0),
(838, 210, 'Chuẩn hóa dữ liệu', 0),
(839, 210, 'Mã hóa label', 0),
(840, 210, 'Tạo đặc trưng mới từ dữ liệu', 1),
(841, 211, 'Dựa vào thống kê thuần túy', 0),
(842, 211, 'Sử dụng luật logic', 0),
(843, 211, 'Chạy theo tuyến tính đơn giản', 0),
(844, 211, 'Từ dữ liệu đầu vào tới đầu ra qua lớp ẩn', 1),
(845, 212, 'Chia nhỏ tập dữ liệu', 0),
(846, 212, 'Giảm số lớp mạng', 0),
(847, 212, 'Giúp mạng học phi tuyến tính', 1),
(848, 212, 'Tăng kích thước dữ liệu', 0),
(849, 213, 'Tăng số lớp ẩn', 0),
(850, 213, 'Chạy mạng nhiều lần', 0),
(851, 213, 'Lan truyền sai số ngược để cập nhật trọng số', 1),
(852, 213, 'Chia dữ liệu thành nhiều phần', 0),
(853, 214, 'Dựa trên thống kê truyền thống', 0),
(854, 214, 'Mạng chuyên xử lý ảnh', 1),
(855, 214, 'Mạng có vòng lặp', 0),
(856, 214, 'Tăng dần số lớp ẩn', 0),
(857, 215, 'Mạng học chuỗi thời gian', 1),
(858, 215, 'Xử lý dữ liệu thiếu', 0),
(859, 215, 'Dùng cho ảnh tĩnh', 0),
(860, 215, 'Tối ưu hóa trọng số', 0),
(861, 216, 'Dùng trong mạng GAN', 0),
(862, 216, 'Mạng đơn lớp', 0),
(863, 216, 'Một loại SVM', 0),
(864, 216, 'Loại RNN nhớ dài hạn', 1),
(865, 217, 'Dùng cho phân cụm', 0),
(866, 217, 'Tạo dữ liệu mới bằng hai mạng đối kháng', 1),
(867, 217, 'Phân lớp dữ liệu', 0),
(868, 217, 'Nén dữ liệu', 0),
(869, 218, 'Tăng số lớp mạng', 0),
(870, 218, 'Tăng batch size', 0),
(871, 218, 'Thêm dữ liệu mới', 0),
(872, 218, 'Giảm overfitting bằng cách bỏ ngẫu nhiên neuron', 1),
(873, 219, 'Chuẩn hóa đầu ra mỗi lớp', 1),
(874, 219, 'Thay đổi kiến trúc mạng', 0),
(875, 219, 'Giảm số đặc trưng', 0),
(876, 219, 'Tăng tốc độ học', 0),
(877, 220, 'Thêm dữ liệu không liên quan', 0),
(878, 220, 'Dùng Adam, SGD,... để tối ưu trọng số', 1),
(879, 220, 'Tăng số đặc trưng', 0),
(880, 220, 'Giảm số lớp', 0),
(881, 241, 'Nhận dạng hình ảnh', 1),
(882, 241, 'Phân tích số liệu', 0),
(883, 241, 'Phân tích văn bản', 0),
(884, 241, 'Nhận diện giọng nói', 0),
(885, 242, 'Control Node Network', 0),
(886, 242, 'Convolutional Neural Network', 1),
(887, 242, 'Central Neural Node', 0),
(888, 242, 'Common Neuron Net', 0),
(889, 243, 'Chia ảnh thành vùng nhỏ', 0),
(890, 243, 'Tăng dữ liệu ảnh', 0),
(891, 243, 'Xác định vị trí và loại đối tượng', 1),
(892, 243, 'Nén ảnh đầu vào', 0),
(893, 244, 'Ghép nhiều ảnh lại với nhau', 0),
(894, 244, 'Xoay ảnh theo góc cố định', 0),
(895, 244, 'Chia ảnh thành các vùng có ý nghĩa', 1),
(896, 244, 'Làm mờ toàn bộ ảnh', 0),
(897, 245, 'Yolo là kỹ thuật nén ảnh', 0),
(898, 245, 'Yolo dùng trong xử lý ngôn ngữ', 0),
(899, 245, 'Yolo là phần mềm chỉnh ảnh', 0),
(900, 245, 'You Only Look Once – mô hình phát hiện đối tượng', 1),
(901, 246, 'Dùng đặc trưng khuôn mặt và mô hình học máy', 1),
(902, 246, 'So khớp màu da', 0),
(903, 246, 'Phân tích tiếng nói', 0),
(904, 246, 'Vẽ lại khuôn mặt thủ công', 0),
(905, 247, 'Lập trình trang web', 0),
(906, 247, 'Thiết kế hệ thống mạng', 0),
(907, 247, 'Xe tự lái, giám sát an ninh', 1),
(908, 247, 'Gửi email tự động', 0),
(909, 248, 'Tiền xử lý, trích đặc trưng, huấn luyện', 1),
(910, 248, 'Xoay ảnh ngẫu nhiên', 0),
(911, 248, 'Gửi ảnh qua mạng', 0),
(912, 248, 'Sao chép ảnh', 0),
(913, 249, 'Phóng to ảnh', 0),
(914, 249, 'Thêm màu cho ảnh', 0),
(915, 249, 'Chia nhỏ ảnh thành ô vuông', 0),
(916, 249, 'Trích các đặc trưng quan trọng từ ảnh', 1),
(917, 250, 'Trình duyệt web', 0),
(918, 250, 'Ngôn ngữ lập trình mới', 0),
(919, 250, 'Thư viện xử lý ảnh mã nguồn mở', 1),
(920, 250, 'Bộ xử lý văn bản', 0),
(921, 251, 'In ảnh ra giấy', 0),
(922, 251, 'Thay đổi tên file ảnh', 0),
(923, 251, 'Thêm hiệu ứng ảnh', 0),
(924, 251, 'Chuẩn bị dữ liệu, chọn mô hình, huấn luyện', 1),
(925, 252, 'Chuyển đổi ngôn ngữ', 0),
(926, 252, 'Tăng cường dữ liệu ảnh bằng biến đổi', 1),
(927, 252, 'Tăng tốc độ xử lý CPU', 0),
(928, 252, 'Xóa bớt dữ liệu', 0),
(929, 253, 'Đếm số lượng ảnh', 0),
(930, 253, 'Dùng độ chính xác, recall, F1-score', 1),
(931, 253, 'So màu với ảnh mẫu', 0),
(932, 253, 'Dựa vào kích thước ảnh', 0),
(933, 254, 'Dịch ngôn ngữ', 0),
(934, 254, 'Tăng độ sáng ảnh', 0),
(935, 254, 'Dùng mô hình có sẵn để huấn luyện tiếp', 1),
(936, 254, 'Tạo video từ ảnh', 0),
(937, 255, 'Trình phát video', 0),
(938, 255, 'Mạng CNN có kiến trúc sâu', 1),
(939, 255, 'Công cụ học tiếng Anh', 0),
(940, 255, 'Phần mềm chỉnh âm thanh', 0),
(941, 256, 'Chạy đa nhiệm hiệu quả', 0),
(942, 256, 'Có tri thức chuyên gia và suy diễn', 1),
(943, 256, 'Dùng để chơi game', 0),
(944, 256, 'Ghi âm cuộc gọi', 0),
(945, 257, 'Giải phương trình toán', 0),
(946, 257, 'Quét virus máy tính', 0),
(947, 257, 'Dịch văn bản tự động', 0),
(948, 257, 'Hỗ trợ quyết định chuyên môn', 1),
(949, 258, 'Bộ phát wifi', 0),
(950, 258, 'Lưu trữ tri thức chuyên gia', 1),
(951, 258, 'Cơ sở dữ liệu khách hàng', 0),
(952, 258, 'Phần mềm điều khiển', 0),
(953, 259, 'Chương trình vẽ hình', 0),
(954, 259, 'Bộ nhớ RAM', 0),
(955, 259, 'Cảm biến hình ảnh', 0),
(956, 259, 'Thành phần suy luận trong hệ thống', 1),
(957, 260, 'Chơi nhạc online', 0),
(958, 260, 'Dạy vẽ tranh', 0),
(959, 260, 'Trình bày slide', 0),
(960, 260, 'Y tế, tài chính, kỹ thuật', 1),
(961, 221, 'Là thuật toán tối ưu dùng để cập nhật trọng số mô hình bằng cách giảm dần đạo hàm hàm mất mát', 1),
(962, 221, 'Một loại mạng nơ-ron', 0),
(963, 221, 'Hàm tính khoảng cách giữa hai điểm dữ liệu', 0),
(964, 221, 'Kỹ thuật phân cụm dữ liệu', 0),
(965, 222, 'Là một kỹ thuật tăng cường dữ liệu', 0),
(966, 222, 'Là một loại mạng nơ-ron tích chập', 0),
(967, 222, 'Là phương pháp giảm số chiều dữ liệu', 0),
(968, 222, 'Là thuật toán tối ưu sử dụng adaptive learning rate và momentum', 1),
(969, 223, 'Là kỹ thuật tận dụng mô hình đã huấn luyện để áp dụng cho tác vụ mới', 1),
(970, 223, 'Là thuật toán học không giám sát', 0),
(971, 223, 'Là kỹ thuật tăng kích thước tập dữ liệu', 0),
(972, 223, 'Là cách huấn luyện mạng nơ-ron từ đầu', 0),
(973, 224, 'Loại bỏ lớp ẩn trong mô hình', 0),
(974, 224, 'Sử dụng regularization, dropout hoặc tăng dữ liệu', 1),
(975, 224, 'Giảm kích thước tập dữ liệu', 0),
(976, 224, 'Tăng số lượng epoch huấn luyện', 0),
(977, 225, 'Là phần mềm thiết kế giao diện người dùng', 0),
(978, 225, 'Là một thư viện mã nguồn mở dùng để xây dựng mô hình học sâu', 1),
(979, 225, 'Là hệ điều hành chuyên dụng cho AI', 0),
(980, 225, 'Là công cụ tạo cơ sở dữ liệu', 0),
(981, 226, 'Trong chatbot, phân tích cảm xúc, dịch máy,...', 1),
(982, 226, 'Chỉ dùng trong xử lý ảnh', 0),
(983, 226, 'Chỉ dùng trong thiết kế web', 0),
(984, 226, 'Không có ứng dụng thực tiễn', 0),
(985, 227, 'Dựa vào cảm biến phần cứng', 0),
(986, 227, 'Sử dụng NLP và AI để hiểu và phản hồi người dùng', 1),
(987, 227, 'Chỉ sử dụng các câu trả lời cố định', 0),
(988, 227, 'Dùng hệ điều hành đặc biệt để phản hồi', 0),
(989, 228, 'Là quá trình tách văn bản thành các đơn vị nhỏ như từ hoặc cụm từ', 1),
(990, 228, 'Là bước đánh giá mô hình', 0),
(991, 228, 'Là công cụ phát hiện ngôn ngữ', 0),
(992, 228, 'Là kỹ thuật tăng dữ liệu', 0),
(993, 229, 'Là phương pháp mã hóa hình ảnh', 0),
(994, 229, 'Là kỹ thuật biểu diễn từ thành vector trong không gian liên tục', 1),
(995, 229, 'Là bước làm sạch dữ liệu', 0),
(996, 229, 'Là một kiểu phân loại dữ liệu', 0),
(997, 230, 'Là phần mềm dịch văn bản', 0),
(998, 230, 'Là mô hình NLP tiền huấn luyện của Google cho nhiều tác vụ ngôn ngữ', 1),
(999, 230, 'Là hệ điều hành cho NLP', 0),
(1000, 230, 'Là bộ công cụ xây dựng chatbot', 0),
(1001, 231, 'Là công cụ kiểm tra chính tả', 0),
(1002, 231, 'Là kỹ thuật dịch ngôn ngữ tự động', 0),
(1003, 231, 'Là quá trình phân tích cảm xúc trong văn bản', 1),
(1004, 231, 'Là bước mã hóa từ vựng', 0),
(1005, 232, 'Là thuật toán học tăng cường', 0),
(1006, 232, 'Là bước trích xuất dữ liệu', 0),
(1007, 232, 'Là phương pháp tạo từ điển', 0),
(1008, 232, 'Là quá trình gán loại từ cho từng từ trong văn bản', 1),
(1009, 233, 'Là kỹ thuật nhận diện thực thể có tên trong văn bản', 1),
(1010, 233, 'Là phần mềm dịch văn bản', 0),
(1011, 233, 'Là thuật toán phân cụm dữ liệu', 0),
(1012, 233, 'Là bộ lọc dữ liệu đầu vào', 0),
(1013, 234, 'Là thuật toán học tăng cường', 0),
(1014, 234, 'Là kỹ thuật tạo mô hình 3D', 0),
(1015, 234, 'Là quá trình dịch văn bản từ ngôn ngữ này sang ngôn ngữ khác', 1),
(1016, 234, 'Là bước đánh giá cảm xúc', 0),
(1017, 235, 'Gồm các bước: làm sạch, tách từ, loại bỏ stopwords,...', 1),
(1018, 235, 'Tăng độ dài văn bản đầu vào', 0),
(1019, 235, 'Thêm lỗi chính tả để kiểm tra mô hình', 0),
(1020, 235, 'Chuyển văn bản thành ảnh', 0),
(1021, 236, 'Là bước thu thập dữ liệu', 0),
(1022, 236, 'Là công cụ dịch thuật', 0),
(1023, 236, 'Là kỹ thuật đo tầm quan trọng của từ trong văn bản', 1),
(1024, 236, 'Là phương pháp vẽ đồ thị', 0),
(1025, 237, 'Dùng để hiểu và phản hồi người dùng tự nhiên hơn', 1),
(1026, 237, 'Là công cụ học tăng cường', 0),
(1027, 237, 'Không liên quan đến xử lý ngôn ngữ', 0),
(1028, 237, 'Chỉ để trang trí giao diện', 0),
(1029, 238, 'Chỉ cần thu thập dữ liệu', 0),
(1030, 238, 'Gồm chuẩn bị dữ liệu, huấn luyện mô hình và đánh giá', 1),
(1031, 238, 'Không cần đánh giá kết quả', 0),
(1032, 238, 'Chạy mô hình mà không huấn luyện', 0),
(1033, 239, 'Là các từ khóa chính của văn bản', 0),
(1034, 239, 'Là lỗi chính tả cần sửa', 0),
(1035, 239, 'Là những từ thường xuất hiện nhưng ít mang ý nghĩa', 1),
(1036, 239, 'Là từ vựng chuyên ngành', 0),
(1037, 240, 'Dựa vào độ dài câu trả lời', 0),
(1038, 240, 'Không cần đánh giá mô hình NLP', 0),
(1039, 240, 'Dùng độ chính xác, F1-score,... để đánh giá', 1),
(1040, 240, 'Bằng cách kiểm tra chính tả', 0),
(1041, 261, 'AI học từ dữ liệu', 1),
(1042, 261, 'ES không tự học', 0),
(1043, 261, 'ES dựa vào luật', 0),
(1044, 261, 'AI cần big data', 0),
(1045, 262, 'Hệ thống học sâu', 0),
(1046, 262, 'Dựa vào luật IF-THEN', 1),
(1047, 262, 'Tự sinh luật', 0),
(1048, 262, 'Không có tri thức', 0),
(1049, 263, 'Gồm tri thức, suy luận', 1),
(1050, 263, 'Không cần luật', 0),
(1051, 263, 'Không cần chuyên gia', 0),
(1052, 263, 'Dựa vào cảm biến', 0),
(1053, 264, 'Cần dữ liệu lớn', 0),
(1054, 264, 'Tự học nhanh', 0),
(1055, 264, 'Không thích nghi tốt', 1),
(1056, 264, 'Giải quyết mọi bài toán', 0),
(1057, 265, 'Hỗ trợ chẩn đoán', 1),
(1058, 265, 'Dựa vào AI học sâu', 0),
(1059, 265, 'Tự thay bác sĩ', 0),
(1060, 265, 'Không cần dữ liệu', 0),
(1061, 266, 'ES không học', 1),
(1062, 266, 'ES dùng mạng nơ-ron', 0),
(1063, 266, 'AI không suy luận', 0),
(1064, 266, 'AI không có luật', 0),
(1065, 267, 'Tự động qua cảm biến', 0),
(1066, 267, 'Thông qua chuyên gia', 1),
(1067, 267, 'Không thể cập nhật', 0),
(1068, 267, 'Từ dữ liệu ngẫu nhiên', 0),
(1069, 268, 'Quản lý ruộng lúa', 0),
(1070, 268, 'Lái xe tự động', 0),
(1071, 268, 'Phát hiện xâm nhập', 0),
(1072, 268, 'Phân tích đầu tư', 1),
(1073, 269, 'C++', 0),
(1074, 269, 'Prolog', 1),
(1075, 269, 'HTML', 0),
(1076, 269, 'Python', 0),
(1077, 270, 'Word', 0),
(1078, 270, 'Excel', 0),
(1079, 270, 'Photoshop', 0),
(1080, 270, 'CLIPS', 1),
(1081, 271, 'Chơi game', 0),
(1082, 271, 'Dự báo thời tiết', 0),
(1083, 271, 'Chẩn đoán bệnh', 1),
(1084, 271, 'Lái xe', 0),
(1085, 272, 'Dùng cảm biến & AI', 1),
(1086, 272, 'Không dùng camera', 0),
(1087, 272, 'Chạy theo lộ trình cứng', 0),
(1088, 272, 'Dựa vào tay lái người', 0),
(1089, 273, 'Dự báo thời tiết', 0),
(1090, 273, 'Gợi ý sản phẩm', 1),
(1091, 273, 'Làm toán', 0),
(1092, 273, 'Chơi nhạc', 0),
(1093, 274, 'Tùy biến bài học', 1),
(1094, 274, 'Chẩn đoán bệnh', 0),
(1095, 274, 'Giao tiếp mạng', 0),
(1096, 274, 'Lái xe', 0),
(1097, 275, 'Phân loại giống cây', 0),
(1098, 275, 'Phát hiện tấn công', 1),
(1099, 275, 'Tưới nước tự động', 0),
(1100, 275, 'Tính lương', 0),
(1101, 276, 'Phân tích đất đai', 1),
(1102, 276, 'Quản lý ngân hàng', 0),
(1103, 276, 'Lập trình web', 0),
(1104, 276, 'Chơi game', 0),
(1105, 277, 'Phân tích văn bản', 0),
(1106, 277, 'Tự động hóa dây chuyền', 1),
(1107, 277, 'Tư vấn học tập', 0),
(1108, 277, 'Phát sóng truyền hình', 0),
(1109, 278, 'Dự báo mưa', 0),
(1110, 278, 'Phân tích rủi ro', 1),
(1111, 278, 'Chạy quảng cáo', 0),
(1112, 278, 'Chơi nhạc', 0),
(1113, 279, 'Tối ưu vận chuyển', 1),
(1114, 279, 'Chạy game', 0),
(1115, 279, 'Quản lý bài giảng', 0),
(1116, 279, 'Tính điểm thi', 0),
(1117, 280, 'Đo nhiệt độ', 0),
(1118, 280, 'Gửi hàng', 0),
(1119, 280, 'Chatbot hỗ trợ', 1),
(1120, 280, 'Tưới cây', 0),
(1121, 281, 'Tạo đối thủ thông minh', 1),
(1122, 281, 'Phân tích xét nghiệm', 0),
(1123, 281, 'Lập lịch học', 0),
(1124, 281, 'Tạo website', 0),
(1125, 282, 'Dễ học', 0),
(1126, 282, 'Thiếu cảm xúc', 1),
(1127, 282, 'Không cần dữ liệu', 0),
(1128, 282, 'Tự động học mọi thứ', 0),
(1129, 283, 'Tự suy luận', 0),
(1130, 283, 'Cảm nhận người dùng', 0),
(1131, 283, 'Đo bằng mắt', 0),
(1132, 283, 'So sánh kết quả thực tế', 1),
(1133, 284, 'Dự báo thị trường', 0),
(1134, 284, 'Chạy quảng cáo', 0),
(1135, 284, 'Nhận dạng hình ảnh y tế', 1),
(1136, 284, 'Tạo báo cáo thời tiết', 0),
(1137, 285, 'Phân tích dữ liệu thời tiết', 1),
(1138, 285, 'Vẽ tranh', 0),
(1139, 285, 'Dự đoán game thắng', 0),
(1140, 285, 'Gửi mail tự động', 0),
(1141, 286, 'Ngôn ngữ đánh dấu siêu văn bản', 0),
(1142, 286, 'Ngôn ngữ lập trình hệ thống', 0),
(1143, 286, 'Ngôn ngữ lập trình hướng đối tượng', 1),
(1144, 286, 'Ngôn ngữ truy vấn cơ sở dữ liệu', 0),
(1145, 287, 'Flutter chỉ chạy trên Android, React Native chạy đa nền tảng', 0),
(1146, 287, 'Flutter dựa trên HTML còn React Native thì không', 0),
(1147, 287, 'Flutter sử dụng Dart, React Native dùng JavaScript', 1),
(1148, 287, 'Flutter viết bằng Python còn React Native viết bằng Java', 0),
(1149, 288, 'Hiệu suất cao, giao diện đẹp và hot reload', 1),
(1150, 288, 'Không thể tùy biến giao diện', 0),
(1151, 288, 'Không có cộng đồng phát triển', 0),
(1152, 288, 'Chỉ hỗ trợ Android', 0),
(1153, 289, 'Dart chỉ hỗ trợ lập trình hàm', 0),
(1154, 289, 'Chỉ hỗ trợ kế thừa, không hỗ trợ đa hình', 0),
(1155, 289, 'Không, Dart là ngôn ngữ thủ tục', 0),
(1156, 289, 'Có, Dart hỗ trợ đầy đủ OOP', 1),
(1157, 290, 'Ứng dụng di động đa nền tảng', 1),
(1158, 290, 'Ứng dụng hệ thống', 0),
(1159, 290, 'Trang web HTML đơn giản', 0),
(1160, 290, 'Phần mềm máy tính để bàn', 0),
(1161, 291, 'Tính năng cập nhật nhanh mà không khởi động lại ứng dụng', 1),
(1162, 291, 'Chạy lại toàn bộ hệ thống', 0),
(1163, 291, 'Công cụ gỡ lỗi trong Dart', 0),
(1164, 291, 'Xoá dữ liệu và reset thiết bị', 0),
(1165, 292, 'Flutter framework, Dart SDK, và DevTools', 1),
(1166, 292, 'HTML và CSS', 0),
(1167, 292, 'JDK và Android Studio', 0),
(1168, 292, 'React, Vue, và Angular', 0),
(1169, 293, 'Tải Flutter SDK và cấu hình PATH', 1),
(1170, 293, 'Cài đặt Java JDK', 0),
(1171, 293, 'Dùng npm install', 0),
(1172, 293, 'Cài đặt Photoshop', 0),
(1173, 294, 'Android Studio, VS Code', 1),
(1174, 294, 'Chrome DevTools', 0),
(1175, 294, 'MS Paint', 0),
(1176, 294, 'Word và Excel', 0),
(1177, 295, 'Chỉ dùng cho lập trình nhúng', 0),
(1178, 295, 'Có, Flutter hỗ trợ Android, iOS, web, desktop', 1),
(1179, 295, 'Không, chỉ hỗ trợ Android', 0),
(1180, 295, 'Chỉ hỗ trợ Windows', 0),
(1181, 296, 'Không có hot reload', 0),
(1182, 296, 'Dung lượng ứng dụng lớn, thư viện hạn chế', 1),
(1183, 296, 'Không hỗ trợ lập trình hướng đối tượng', 0),
(1184, 296, 'Không chạy được trên thiết bị di động', 0),
(1185, 297, 'Native app được viết bằng HTML', 0),
(1186, 297, 'Flutter không hỗ trợ giao diện người dùng', 0),
(1187, 297, 'Flutter là cross-platform, native là nền tảng riêng', 1),
(1188, 297, 'Flutter chỉ chạy trên Windows', 0),
(1189, 298, 'Không, Dart chỉ hỗ trợ đồng bộ', 0),
(1190, 298, 'Async là thư viện ngoài', 0),
(1191, 298, 'Có, Dart hỗ trợ async/await', 1),
(1192, 298, 'Dart không có hàm', 0),
(1193, 299, 'Là thành phần giao diện trong Flutter', 1),
(1194, 299, 'Là một IDE', 0),
(1195, 299, 'Là cơ sở dữ liệu', 0),
(1196, 299, 'Là ngôn ngữ lập trình', 0),
(1197, 300, 'Mở file Excel mẫu', 0),
(1198, 300, 'Sử dụng lệnh flutter create ten_du_an', 1),
(1199, 300, 'Dùng npm init', 0),
(1200, 300, 'Tạo bằng Photoshop', 0),
(1201, 301, 'Không cần khai báo biến', 0),
(1202, 301, 'Khai báo bằng var, final, const', 1),
(1203, 301, 'Dùng keyword “let”', 0),
(1204, 301, 'Chỉ dùng int và string', 0),
(1205, 302, 'Xử lý bất đồng bộ mạch lạc', 1),
(1206, 302, 'Tạo UI động', 0),
(1207, 302, 'Gọi API đồng bộ', 0),
(1208, 302, 'Tạo biến toàn cục', 0),
(1209, 303, 'Dùng từ khóa def', 0),
(1210, 303, 'Dùng từ khóa class', 1),
(1211, 303, 'Dùng widget builder', 0),
(1212, 303, 'Dùng hàm main', 0),
(1213, 304, 'Không có tham số', 0),
(1214, 304, 'Chỉ dùng để vẽ giao diện', 0),
(1215, 304, 'Nhận tham số và trả về giá trị', 1),
(1216, 304, 'Không trả về giá trị', 0),
(1217, 305, 'Tự động cập nhật UI', 0),
(1218, 305, 'Giúp tránh lỗi null trong chương trình', 1),
(1219, 305, 'Tăng tốc độ kết nối mạng', 0),
(1220, 305, 'Giảm kích thước ứng dụng', 0),
(1221, 306, 'Dùng dấu <>', 0),
(1222, 306, 'Dùng keyword map', 0),
(1223, 306, 'Dùng cú pháp [1, 2, 3]', 1),
(1224, 306, 'Dùng dấu {}', 0),
(1225, 307, 'Kiểu dữ liệu boolean', 0),
(1226, 307, 'Danh sách có thứ tự', 0),
(1227, 307, 'Cấu trúc dữ liệu lưu key-value', 1),
(1228, 307, 'Một loại hàm', 0),
(1229, 308, 'Không cần xử lý lỗi', 0),
(1230, 308, 'Dùng if-else để bắt lỗi', 0),
(1231, 308, 'Dùng vòng lặp', 0),
(1232, 308, 'Dùng try-catch để bắt lỗi', 1),
(1233, 309, 'Một hàm tạo giao diện', 0),
(1234, 309, 'Một kiểu biến đặc biệt', 0),
(1235, 309, 'Là đối tượng đại diện cho giá trị bất đồng bộ', 1),
(1236, 309, 'Một loại exception', 0),
(1237, 310, 'Một biến số nguyên', 0),
(1238, 310, 'Một kiểu định danh biến', 0),
(1239, 310, 'Là luồng dữ liệu bất đồng bộ', 1),
(1240, 310, 'Một kiểu widget', 0),
(1241, 311, 'const dùng để khai báo hằng số không thay đổi tại thời điểm biên dịch', 1);
INSERT INTO `cautraloi` (`macautl`, `macauhoi`, `noidungtl`, `ladapan`) VALUES
(1242, 311, 'const dùng để định nghĩa hàm', 0),
(1243, 311, 'const là từ khóa dùng để khai báo lớp', 0),
(1244, 311, 'const dùng để tạo biến toàn cục', 0),
(1245, 312, 'Mixin là widget đặc biệt', 0),
(1246, 312, 'Mixin là một loại biến toàn cục', 0),
(1247, 312, 'Mixin là một loại hàm trong Dart', 0),
(1248, 312, 'Mixin là cách để thêm tính năng vào class mà không cần kế thừa', 1),
(1249, 313, 'enum là một loại class kế thừa', 0),
(1250, 313, 'enum là hàm xử lý lỗi', 0),
(1251, 313, 'enum là kiểu dữ liệu định nghĩa tập hợp các hằng số có tên', 1),
(1252, 313, 'enum là kiểu dữ liệu dạng map', 0),
(1253, 314, 'Extension giúp mở rộng chức năng cho lớp mà không cần sửa mã gốc', 1),
(1254, 314, 'Extension là loại biến toàn cục', 0),
(1255, 314, 'Extension là công cụ vẽ giao diện', 0),
(1256, 314, 'Extension là thư viện hệ thống', 0),
(1257, 315, 'Để tăng tốc độ xử lý của CPU', 0),
(1258, 315, 'Để chuyển đổi sang ngôn ngữ khác', 0),
(1259, 315, 'Để tránh dùng Dart', 0),
(1260, 315, 'Để chia nhỏ giao diện, tái sử dụng code dễ dàng hơn', 1),
(1261, 316, 'StatelessWidget có thể cập nhật UI liên tục', 0),
(1262, 316, 'StatelessWidget không thay đổi trạng thái sau khi xây dựng', 1),
(1263, 316, 'StatelessWidget là widget có bộ nhớ đệm', 0),
(1264, 316, 'StatelessWidget chỉ dùng trong web', 0),
(1265, 317, 'StatefulWidget không dùng được với setState', 0),
(1266, 317, 'StatefulWidget dùng để định nghĩa biến', 0),
(1267, 317, 'StatefulWidget là kiểu dữ liệu', 0),
(1268, 317, 'StatefulWidget có thể thay đổi trạng thái trong quá trình chạy', 1),
(1269, 318, 'setState là hàm tạo widget mới', 0),
(1270, 318, 'setState dùng để cập nhật giao diện khi dữ liệu thay đổi', 1),
(1271, 318, 'setState dùng để khai báo biến', 0),
(1272, 318, 'setState là từ khóa đặc biệt của Dart', 0),
(1273, 319, 'Là loại biến trong Dart', 0),
(1274, 319, 'Là phần tử trong pubspec.yaml', 0),
(1275, 319, 'Widget cơ bản chứa các widget con, ví dụ Column, Row', 1),
(1276, 319, 'Là plugin của Android Studio', 0),
(1277, 320, 'Column sắp xếp widget theo chiều dọc', 1),
(1278, 320, 'Column sắp xếp theo chiều ngang', 0),
(1279, 320, 'Column dùng để xử lý dữ liệu', 0),
(1280, 320, 'Column là một loại biến', 0),
(1281, 331, 'Thiết kế giao diện', 0),
(1282, 331, 'Quản lý trạng thái giữa các widget', 1),
(1283, 331, 'Tạo hiệu ứng động', 0),
(1284, 331, 'Tạo API', 0),
(1285, 332, 'Tách biệt UI và logic thông qua stream và event', 1),
(1286, 332, 'Công cụ xây dựng widget', 0),
(1287, 332, 'Thư viện dựng hình ảnh', 0),
(1288, 332, 'Chạy đa luồng trong Flutter', 0),
(1289, 333, 'Thư viện quản lý trạng thái mới và hiện đại hơn Provider', 1),
(1290, 333, 'Công cụ tạo animation', 0),
(1291, 333, 'Giao diện cho cơ sở dữ liệu', 0),
(1292, 333, 'Một kiểu dữ liệu trong Dart', 0),
(1293, 334, 'Dùng widget Stateless để lưu trạng thái', 0),
(1294, 334, 'Không cần quản lý trạng thái', 0),
(1295, 334, 'Dùng biến toàn cục', 0),
(1296, 334, 'Sử dụng các package như Provider, Bloc, Riverpod...', 1),
(1297, 335, 'Thư viện dựng đồ thị', 0),
(1298, 335, 'Plugin cho DartPad', 0),
(1299, 335, 'Công cụ thiết kế UI', 0),
(1300, 335, 'Một framework giúp quản lý trạng thái và routing dễ dàng', 1),
(1301, 336, 'Tạo route mới', 0),
(1302, 336, 'Hiển thị hình ảnh động', 0),
(1303, 336, 'Dùng để cập nhật giao diện theo stream dữ liệu', 1),
(1304, 336, 'Dùng để khai báo biến', 0),
(1305, 337, 'Sử dụng HTML để xử lý sự kiện', 0),
(1306, 337, 'Sử dụng các widget như GestureDetector, InkWell', 1),
(1307, 337, 'Không cần xử lý sự kiện trong Flutter', 0),
(1308, 337, 'Chỉ dùng hàm print', 0),
(1309, 338, 'Lưu trữ dữ liệu mạng', 0),
(1310, 338, 'Chia sẻ dữ liệu xuống cây widget mà không cần truyền qua constructor', 1),
(1311, 338, 'Hiển thị hiệu ứng', 0),
(1312, 338, 'Tạo widget dạng danh sách', 0),
(1313, 339, 'Công cụ tạo API', 0),
(1314, 339, 'Widget hiển thị hình ảnh', 0),
(1315, 339, 'Lớp thông báo thay đổi để cập nhật UI', 1),
(1316, 339, 'Thư viện vẽ đồ họa', 0),
(1317, 340, 'Không dùng widget Stateful', 0),
(1318, 340, 'Chỉ dùng biến static', 0),
(1319, 340, 'Sử dụng state management phù hợp, tránh rebuild không cần thiết', 1),
(1320, 340, 'Tạo nhiều biến toàn cục', 0),
(1321, 341, 'Khi không có state nào', 0),
(1322, 341, 'Chỉ khi dùng với Bloc', 0),
(1323, 341, 'Khi UI cố định, không thay đổi', 0),
(1324, 341, 'Khi giao diện cần thay đổi theo dữ liệu', 1),
(1325, 342, 'Không cần gọi setState bao giờ', 0),
(1326, 342, 'Chỉ gọi setState khi dùng Provider', 0),
(1327, 342, 'Dùng setState để khai báo biến', 0),
(1328, 342, 'Gọi setState trong widget để cập nhật giao diện', 1),
(1329, 343, 'Là một notifier đơn giản cho 1 giá trị và listener', 1),
(1330, 343, 'Một loại biến đặc biệt', 0),
(1331, 343, 'Thư viện quản lý routing', 0),
(1332, 343, 'Là widget cho animation', 0),
(1333, 344, 'Dùng để tạo nút bấm', 0),
(1334, 344, 'Không hỗ trợ async', 0),
(1335, 344, 'Chỉ hoạt động với Bloc', 0),
(1336, 344, 'Dùng để xây dựng UI dựa trên Future', 1),
(1337, 345, 'Sử dụng biến toàn cục để xử lý', 0),
(1338, 345, 'Không xử lý lỗi trong trạng thái', 0),
(1339, 345, 'Dùng print để xử lý lỗi', 0),
(1340, 345, 'Bắt lỗi bằng try-catch, logging và fallback UI', 1),
(1341, 346, 'Dùng HTML để gọi API', 0),
(1342, 346, 'Sử dụng http hoặc dio để gửi request và nhận response', 1),
(1343, 346, 'Flutter không hỗ trợ gọi API', 0),
(1344, 346, 'Chỉ dùng khi build trên Android', 0),
(1345, 347, 'Chỉ cần tạo tài khoản Google', 0),
(1346, 347, 'Firebase chỉ hỗ trợ web', 0),
(1347, 347, 'Kết nối thông qua Firebase SDK, cấu hình project và sử dụng plugin', 1),
(1348, 347, 'Không thể tích hợp với Flutter', 0),
(1349, 348, 'Tạo giao diện người dùng', 0),
(1350, 348, 'Dùng để định nghĩa route', 0),
(1351, 348, 'Hiển thị dữ liệu mạng', 0),
(1352, 348, 'Gửi các request HTTP như GET, POST', 1),
(1353, 349, 'Một công cụ viết mã', 0),
(1354, 349, 'Thư viện mạnh mẽ để thực hiện các request HTTP', 1),
(1355, 349, 'Dùng để tạo widget', 0),
(1356, 349, 'Dùng để lưu trữ dữ liệu offline', 0),
(1357, 350, 'Sử dụng plugin cloud_firestore và thao tác với Collection/Document', 1),
(1358, 350, 'Flutter không hỗ trợ Firestore', 0),
(1359, 350, 'Chỉ dùng Firestore cho Android', 0),
(1360, 350, 'Phải dùng SQLite thay thế', 0),
(1361, 365, 'Quản lý trạng thái ứng dụng', 0),
(1362, 365, 'Thư viện điều hướng', 0),
(1363, 365, 'Dùng để tạo giao diện người dùng', 0),
(1364, 365, 'Sử dụng để viết unit test cho ứng dụng Flutter', 1),
(1365, 366, 'Bằng cách cập nhật pubspec.yaml', 0),
(1366, 366, 'Sử dụng các công cụ như DevTools và debugPrint', 1),
(1367, 366, 'Sử dụng Firebase để debug', 0),
(1368, 366, 'Bằng cách xóa file build', 0),
(1369, 367, 'Cài đặt gói từ pub.dev', 0),
(1370, 367, 'Lưu trữ dữ liệu người dùng', 0),
(1371, 367, 'Phân tích hiệu suất, memory và layout của ứng dụng', 1),
(1372, 367, 'Tối ưu hóa hình ảnh', 0),
(1373, 368, 'Dùng const constructors, tránh rebuild không cần thiết', 1),
(1374, 368, 'Tắt chế độ release', 0),
(1375, 368, 'Sử dụng nhiều hình ảnh độ phân giải cao', 0),
(1376, 368, 'Tăng số lượng widget con', 0),
(1377, 369, 'Sao chép mã nguồn sang Android Studio', 0),
(1378, 369, 'Chạy ứng dụng trên iOS', 0),
(1379, 369, 'Tạo bản phát hành, ký ứng dụng, và tải lên Google Play Console', 1),
(1380, 369, 'Tạo tài khoản App Store', 0),
(1381, 370, 'Tắt chế độ debug', 0),
(1382, 370, 'Tải lên Firebase Hosting', 0),
(1383, 370, 'Chạy ứng dụng trong Chrome', 0),
(1384, 370, 'Chuẩn bị cấu hình, ký ứng dụng và sử dụng Xcode để xuất bản', 1),
(1385, 371, 'Là quá trình ký ứng dụng để xác thực nguồn gốc và tính toàn vẹn', 1),
(1386, 371, 'Dùng để quản lý version code', 0),
(1387, 371, 'Tối ưu hóa hiệu suất UI', 0),
(1388, 371, 'Là quá trình nén ảnh trong ứng dụng', 0),
(1389, 372, 'Bằng cách xoá toàn bộ widget', 0),
(1390, 372, 'Ẩn thông báo lỗi', 0),
(1391, 372, 'Cập nhật phiên bản Flutter', 0),
(1392, 372, 'Sử dụng try-catch, FlutterError.onError và logging', 1),
(1393, 373, 'Loại bỏ mã không dùng, sử dụng chế độ release', 1),
(1394, 373, 'Tắt code minification', 0),
(1395, 373, 'Tăng số lượng widget', 0),
(1396, 373, 'Thêm nhiều hình ảnh PNG', 0),
(1397, 374, 'Tăng tốc độ mạng', 0),
(1398, 374, 'Sử dụng DevTools để theo dõi CPU, memory, và rendering', 1),
(1399, 374, 'Xoá thư mục build thường xuyên', 0),
(1400, 374, 'Tạo nhiều thread xử lý song song', 0),
(1401, 375, 'Sử dụng Flutter DevTools để theo dõi hiệu suất thời gian thực', 1),
(1402, 375, 'Sử dụng emulator cũ', 0),
(1403, 375, 'Chạy nhiều ứng dụng cùng lúc', 0),
(1404, 375, 'Tăng độ phân giải ảnh', 0),
(1405, 351, 'Chỉ dùng trong backend', 0),
(1406, 351, 'Thông qua gói flutter_local_notifications', 0),
(1407, 351, 'Dùng để lưu trữ dữ liệu nội bộ', 0),
(1408, 351, 'Thông qua các gói như http để gửi yêu cầu tới server', 1),
(1409, 352, 'Sử dụng API REST', 0),
(1410, 352, 'Sử dụng SharedPreferences hoặc SQLite', 1),
(1411, 352, 'Chỉ dùng Cloud Firestore', 0),
(1412, 352, 'Sử dụng Firebase Realtime Database', 0),
(1413, 353, 'Dùng để gửi dữ liệu giữa các màn hình', 0),
(1414, 353, 'Là thư viện để xử lý JSON', 0),
(1415, 353, 'Là phương pháp lưu trữ key-value đơn giản trên thiết bị', 1),
(1416, 353, 'Là một hệ cơ sở dữ liệu NoSQL', 0),
(1417, 354, 'Dùng để lưu trữ dữ liệu dạng key-value', 0),
(1418, 354, 'Chỉ dùng được khi có kết nối internet', 0),
(1419, 354, 'Sử dụng gói sqflite để truy vấn dữ liệu SQL', 1),
(1420, 354, 'SQLite là dịch vụ lưu trữ đám mây của Firebase', 0),
(1421, 355, 'Là plugin để gửi thông báo đẩy', 0),
(1422, 355, 'Là một hệ thống cơ sở dữ liệu trực tuyến', 0),
(1423, 355, 'Là API kết nối mạng trong Flutter', 0),
(1424, 355, 'Là một gói giúp lưu trữ dữ liệu dạng key-value, nhanh và không cần SQL', 1),
(1425, 356, 'Không thể gửi thông báo trong Flutter', 0),
(1426, 356, 'Dùng SharedPreferences để gửi thông báo', 0),
(1427, 356, 'Sử dụng package firebase_messaging', 1),
(1428, 356, 'Dùng sqflite để gửi thông báo', 0),
(1429, 357, 'Chỉ có thể dùng REST trong Flutter', 0),
(1430, 357, 'Sử dụng JSON API mặc định', 0),
(1431, 357, 'Sử dụng gói graphql_flutter để gửi truy vấn và nhận dữ liệu', 1),
(1432, 357, 'Flutter không hỗ trợ GraphQL', 0),
(1433, 358, 'Dùng thư viện dart:html', 0),
(1434, 358, 'Dùng phương thức jsonDecode và jsonEncode từ dart:convert', 1),
(1435, 358, 'Dùng file YAML', 0),
(1436, 358, 'Không thể xử lý JSON trong Flutter', 0),
(1437, 359, 'Flutter không hỗ trợ bản đồ', 0),
(1438, 359, 'Chỉ có thể dùng bản đồ khi lập trình native', 0),
(1439, 359, 'Sử dụng thư viện google_maps_flutter', 1),
(1440, 359, 'Dùng sqflite để hiển thị bản đồ', 0),
(1441, 360, 'Chỉ dùng được với REST API', 0),
(1442, 360, 'Dùng shared_preferences để xác thực', 0),
(1443, 360, 'Không thể xác thực người dùng trong Flutter', 0),
(1444, 360, 'Dùng package firebase_auth hoặc OAuth2', 1),
(1445, 361, 'Dùng Flutter Inspector để test', 0),
(1446, 361, 'Viết test với thư viện test của Dart', 1),
(1447, 361, 'Dùng lệnh flutter generate test', 0),
(1448, 361, 'Kiểm thử chỉ áp dụng cho widget', 0),
(1449, 362, 'GitHub Pages', 0),
(1450, 362, 'SQLite', 0),
(1451, 362, 'Visual Studio Code', 0),
(1452, 362, 'Firebase Hosting, App Store, Google Play', 1),
(1453, 363, 'Kiểm thử các widget riêng lẻ', 0),
(1454, 363, 'Dùng để test mạng', 0),
(1455, 363, 'Kiểm thử tổng thể nhiều phần của ứng dụng chạy cùng nhau', 1),
(1456, 363, 'Là test thủ công', 0),
(1457, 364, 'Kiểm thử giao diện người dùng ở mức widget riêng lẻ', 1),
(1458, 364, 'Dùng để lưu trạng thái widget', 0),
(1459, 364, 'Dùng để test cơ sở dữ liệu', 0),
(1460, 364, 'Kiểm thử API', 0),
(1461, 321, 'Quản lý trạng thái ứng dụng', 0),
(1462, 321, 'Dùng để xếp chồng các widget', 1),
(1463, 321, 'Hiển thị danh sách có cuộn', 0),
(1464, 321, 'Tạo cấu trúc cây cho widget', 0),
(1465, 322, 'Để sắp xếp các widget theo chiều ngang', 0),
(1466, 322, 'Để chồng các widget lên nhau', 0),
(1467, 322, 'Để tạo hộp thoại', 0),
(1468, 322, 'Để hiển thị danh sách cuộn theo chiều dọc hoặc ngang', 1),
(1469, 323, 'Một loại layout xếp chồng widget', 0),
(1470, 323, 'Một widget cho phép bạn trang trí, căn chỉnh và định kích thước widget con', 1),
(1471, 323, 'Một widget để tạo các biểu tượng', 0),
(1472, 323, 'Một loại danh sách có thể cuộn', 0),
(1473, 324, 'Dùng để tạo nút bấm', 0),
(1474, 324, 'Dùng để hiển thị văn bản trên giao diện', 1),
(1475, 324, 'Dùng để quản lý dữ liệu', 0),
(1476, 324, 'Dùng để điều hướng màn hình', 0),
(1477, 325, 'Widget để tạo các biểu tượng', 0),
(1478, 325, 'Widget giúp phát hiện và xử lý các thao tác chạm', 1),
(1479, 325, 'Widget hiển thị hình ảnh', 0),
(1480, 325, 'Widget tạo layout dạng cột', 0),
(1481, 326, 'Dùng ListView để tạo nút', 0),
(1482, 326, 'Dùng AppBar để tạo nút', 0),
(1483, 326, 'Dùng Container để xử lý sự kiện', 0),
(1484, 326, 'Dùng ElevatedButton, TextButton hoặc IconButton', 1),
(1485, 327, 'Dùng widget Image hoặc Image.asset, Image.network', 1),
(1486, 327, 'Dùng Scaffold để quản lý hình ảnh', 0),
(1487, 327, 'Dùng Column để hiển thị ảnh', 0),
(1488, 327, 'Dùng Text để hiển thị hình ảnh', 0),
(1489, 328, 'Hiển thị thanh tiêu đề và các hành động trên đầu màn hình', 1),
(1490, 328, 'Tạo layout dạng danh sách', 0),
(1491, 328, 'Quản lý điều hướng giữa các màn hình', 0),
(1492, 328, 'Hiển thị văn bản có định dạng', 0),
(1493, 329, 'Để hiển thị danh sách', 0),
(1494, 329, 'Để tạo thanh công cụ', 0),
(1495, 329, 'Để điều hướng giữa các màn hình', 1),
(1496, 329, 'Để định dạng văn bản', 0),
(1497, 330, 'Dùng Column và Row để tạo animation', 0),
(1498, 330, 'Dùng Navigator để tạo animation', 0),
(1499, 330, 'Dùng AnimationController, Tween và các widget hỗ trợ animation', 1),
(1500, 330, 'Dùng ListView để làm animation', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietdethi`
--

CREATE TABLE `chitietdethi` (
  `made` int(11) NOT NULL,
  `macauhoi` int(11) NOT NULL,
  `thutu` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietdethi`
--

INSERT INTO `chitietdethi` (`made`, `macauhoi`, `thutu`) VALUES
(3, 181, 9),
(3, 182, 10),
(3, 183, 1),
(3, 184, 4),
(3, 185, 5),
(3, 186, 6),
(3, 187, 2),
(3, 188, 7),
(3, 189, 3),
(3, 190, 8),
(3, 191, 11),
(3, 192, 12),
(3, 202, 13),
(3, 204, 15),
(3, 206, 14),
(4, 182, 3),
(4, 183, 2),
(4, 186, 5),
(4, 188, 6),
(4, 189, 4),
(4, 376, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietketqua`
--

CREATE TABLE `chitietketqua` (
  `makq` int(11) NOT NULL,
  `macauhoi` int(11) NOT NULL,
  `dapanchon` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietnhom`
--

CREATE TABLE `chitietnhom` (
  `manhom` int(11) NOT NULL,
  `manguoidung` varchar(50) NOT NULL DEFAULT '0',
  `hienthi` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietnhom`
--

INSERT INTO `chitietnhom` (`manhom`, `manguoidung`, `hienthi`) VALUES
(1, 'CNTT2211001', 1),
(1, 'CNTT2211002', 1),
(1, 'CNTT2211003', 1),
(1, 'CNTT22110032', 1),
(1, 'CNTT2211004', 1),
(1, 'CNTT2211005', 1),
(1, 'CNTT2211006', 1),
(1, 'CNTT2211007', 1),
(1, 'CNTT2211008', 1),
(1, 'CNTT2211009', 1),
(1, 'CNTT2211010', 1),
(1, 'CNTT2211011', 1),
(1, 'CNTT2211012', 1),
(1, 'CNTT2211013', 1),
(1, 'CNTT2211014', 1),
(1, 'CNTT2211015', 1),
(1, 'CNTT2211016', 1),
(1, 'CNTT2211017', 1),
(1, 'CNTT2211018', 1),
(1, 'CNTT2211019', 1),
(1, 'CNTT2211020', 1),
(1, 'CNTT2211021', 1),
(1, 'CNTT2211022', 1),
(1, 'CNTT2211023', 1),
(1, 'CNTT2211024', 1),
(1, 'CNTT2211025', 1),
(1, 'CNTT2211026', 1),
(1, 'CNTT2211027', 1),
(1, 'CNTT2211028', 1),
(1, 'CNTT2211029', 1),
(1, 'CNTT2211030', 1),
(1, 'CNTT2211031', 1),
(3, 'CNTT2211001', 1),
(3, 'CNTT2211002', 1),
(3, 'CNTT2211003', 1),
(3, 'CNTT22110032', 1),
(3, 'CNTT2211004', 1),
(3, 'CNTT2211005', 1),
(3, 'CNTT2211006', 1),
(3, 'CNTT2211007', 1),
(3, 'CNTT2211008', 1),
(3, 'CNTT2211009', 1),
(3, 'CNTT2211010', 1),
(3, 'CNTT2211011', 1),
(3, 'CNTT2211012', 1),
(3, 'CNTT2211013', 1),
(3, 'CNTT2211014', 1),
(3, 'CNTT2211015', 1),
(3, 'CNTT2211016', 1),
(3, 'CNTT2211017', 1),
(3, 'CNTT2211018', 1),
(3, 'CNTT2211019', 1),
(3, 'CNTT2211020', 1),
(3, 'CNTT2211021', 1),
(3, 'CNTT2211022', 1),
(3, 'CNTT2211023', 1),
(3, 'CNTT2211024', 1),
(3, 'CNTT2211025', 1),
(3, 'CNTT2211026', 1),
(3, 'CNTT2211027', 1),
(3, 'CNTT2211028', 1),
(3, 'CNTT2211029', 1),
(3, 'CNTT2211030', 1),
(3, 'CNTT2211031', 1),
(4, 'HTTT2211003', 1),
(5, 'KTPM2211001', 1),
(5, 'KTPM2211002', 1),
(5, 'KTPM2211003', 1),
(5, 'KTPM2211004', 1),
(5, 'KTPM2211005', 1),
(5, 'KTPM2211006', 1),
(5, 'KTPM2211007', 1),
(5, 'KTPM2211008', 1),
(5, 'KTPM2211009', 1),
(5, 'KTPM2211010', 1),
(5, 'KTPM2211011', 1),
(5, 'KTPM2211012', 1),
(5, 'KTPM2211013', 1),
(5, 'KTPM2211014', 1),
(5, 'KTPM2211015', 1),
(5, 'KTPM2211016', 1),
(5, 'KTPM2211017', 1),
(5, 'KTPM2211018', 1),
(5, 'KTPM2211019', 1),
(5, 'KTPM2211020', 1),
(5, 'KTPM2211021', 1),
(5, 'KTPM2211022', 1),
(5, 'KTPM2211023', 1),
(5, 'KTPM2211024', 1),
(5, 'KTPM2211025', 1),
(5, 'KTPM2211026', 1),
(5, 'KTPM2211027', 1),
(5, 'KTPM2211028', 1),
(5, 'KTPM2211029', 1),
(5, 'KTPM2211030', 1),
(5, 'KTPM2211031', 1);

--
-- Bẫy `chitietnhom`
--
DELIMITER $$
CREATE TRIGGER `update_group_participants_after_delete` AFTER DELETE ON `chitietnhom` FOR EACH ROW UPDATE nhom
SET siso = 
(SELECT count(*) FROM chitietnhom where manhom = OLD.manhom)
WHERE manhom = OLD.manhom
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_group_participants_after_insert` AFTER INSERT ON `chitietnhom` FOR EACH ROW UPDATE nhom
SET siso = 
(SELECT count(*) FROM chitietnhom where manhom = NEW.manhom)
WHERE manhom = NEW.manhom
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietquyen`
--

CREATE TABLE `chitietquyen` (
  `manhomquyen` int(11) NOT NULL,
  `chucnang` varchar(50) NOT NULL,
  `hanhdong` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietquyen`
--

INSERT INTO `chitietquyen` (`manhomquyen`, `chucnang`, `hanhdong`) VALUES
(1, 'cauhoi', 'create'),
(1, 'cauhoi', 'delete'),
(1, 'cauhoi', 'update'),
(1, 'cauhoi', 'view'),
(1, 'chuong', 'create'),
(1, 'chuong', 'delete'),
(1, 'chuong', 'update'),
(1, 'chuong', 'view'),
(1, 'dethi', 'create'),
(1, 'dethi', 'delete'),
(1, 'dethi', 'update'),
(1, 'dethi', 'view'),
(1, 'hocphan', 'create'),
(1, 'hocphan', 'delete'),
(1, 'hocphan', 'update'),
(1, 'hocphan', 'view'),
(1, 'monhoc', 'create'),
(1, 'monhoc', 'delete'),
(1, 'monhoc', 'update'),
(1, 'monhoc', 'view'),
(1, 'nguoidung', 'create'),
(1, 'nguoidung', 'delete'),
(1, 'nguoidung', 'update'),
(1, 'nguoidung', 'view'),
(1, 'nhomquyen', 'create'),
(1, 'nhomquyen', 'delete'),
(1, 'nhomquyen', 'update'),
(1, 'nhomquyen', 'view'),
(1, 'phancong', 'create'),
(1, 'phancong', 'delete'),
(1, 'phancong', 'update'),
(1, 'phancong', 'view'),
(1, 'thongbao', 'create'),
(1, 'thongbao', 'delete'),
(1, 'thongbao', 'update'),
(1, 'thongbao', 'view'),
(1, 'thongke', 'create'),
(1, 'thongke', 'delete'),
(1, 'thongke', 'update'),
(1, 'thongke', 'view'),
(2, 'tghocphan', 'join'),
(2, 'tgthi', 'join');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chitietthongbao`
--

CREATE TABLE `chitietthongbao` (
  `matb` int(11) NOT NULL,
  `manhom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chitietthongbao`
--

INSERT INTO `chitietthongbao` (`matb`, `manhom`) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(4, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chuong`
--

CREATE TABLE `chuong` (
  `machuong` int(11) NOT NULL,
  `tenchuong` varchar(255) NOT NULL,
  `mamonhoc` varchar(20) NOT NULL,
  `trangthai` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `chuong`
--

INSERT INTO `chuong` (`machuong`, `tenchuong`, `mamonhoc`, `trangthai`) VALUES
(1, 'Giới thiệu về Lập trình web', 'LTW001', 1),
(2, 'HTML và CSS cơ bản', 'LTW001', 1),
(3, 'JavaScript và tương tác động', 'LTW001', 1),
(4, 'Phát triển ứng dụng web với Framework', 'LTW001', 1),
(5, 'Thiết kế giao diện người dùng', 'LTW001', 1),
(6, 'Quản lý dữ liệu phía máy chủ', 'LTW001', 1),
(7, 'Cơ bản về Cơ sở dữ liệu', 'CSDL001', 1),
(8, 'Mô hình quan hệ và thiết kế CSDL', 'CSDL001', 1),
(9, 'Ngôn ngữ truy vấn SQL', 'CSDL001', 1),
(10, 'Quản lý giao dịch và bảo mật', 'CSDL001', 1),
(11, 'Tối ưu hóa truy vấn', 'CSDL001', 1),
(12, 'Cơ sở dữ liệu phân tán', 'CSDL001', 1),
(13, 'Cơ sở dữ liệu NoSQL', 'CSDL001', 1),
(14, 'Giới thiệu về Trí tuệ nhân tạo', 'TTNT001', 1),
(15, 'Học máy cơ bản', 'TTNT001', 1),
(16, 'Mạng nơ-ron và học sâu', 'TTNT001', 1),
(17, 'Xử lý ngôn ngữ tự nhiên', 'TTNT001', 1),
(18, 'Thị giác máy tính', 'TTNT001', 1),
(19, 'Hệ thống chuyên gia', 'TTNT001', 1),
(20, 'Ứng dụng AI trong thực tiễn', 'TTNT001', 1),
(21, 'Giới thiệu về Dart và Flutter', 'LTDD001', 1),
(22, 'Ngôn ngữ lập trình Dart', 'LTDD001', 1),
(23, 'Giao diện Flutter Widgets', 'LTDD001', 1),
(24, 'Quản lý trạng thái Flutter', 'LTDD001', 1),
(25, 'Tích hợp API và cơ sở dữ liệu', 'LTDD001', 1),
(26, 'Triển khai và kiểm thử', 'LTDD001', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `danhmucchucnang`
--

CREATE TABLE `danhmucchucnang` (
  `chucnang` varchar(50) NOT NULL,
  `tenchucnang` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `danhmucchucnang`
--

INSERT INTO `danhmucchucnang` (`chucnang`, `tenchucnang`) VALUES
('caidat', 'Cài đặt'),
('cauhoi', 'Quản lý câu hỏi'),
('chuong', 'Quản lý chương'),
('dethi', 'Quản lý đề thi'),
('hocphan', 'Quản lý học phần'),
('monhoc', 'Quản lý môn học'),
('nguoidung', 'Quản lý người dùng'),
('nhomquyen', 'Quản lý nhóm quyền'),
('phancong', 'Quản lý phân công'),
('sinhvien', 'Sinh viên'),
('tghocphan', 'Tham gia học phần'),
('tgthi', 'Tham gia thi'),
('thongbao', 'Thông báo'),
('thongke', 'Thống kê');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dethi`
--

CREATE TABLE `dethi` (
  `made` int(11) NOT NULL,
  `monthi` varchar(20) DEFAULT NULL,
  `nguoitao` varchar(50) DEFAULT NULL,
  `tende` varchar(255) DEFAULT NULL,
  `thoigiantao` datetime DEFAULT current_timestamp(),
  `thoigianthi` int(11) DEFAULT NULL,
  `thoigianbatdau` datetime DEFAULT NULL,
  `thoigianketthuc` datetime DEFAULT NULL,
  `hienthibailam` tinyint(1) DEFAULT NULL,
  `xemdiemthi` tinyint(1) DEFAULT NULL,
  `xemdapan` tinyint(1) DEFAULT NULL,
  `troncauhoi` tinyint(1) DEFAULT NULL,
  `trondapan` tinyint(1) DEFAULT NULL,
  `nopbaichuyentab` tinyint(1) DEFAULT NULL,
  `loaide` int(11) DEFAULT NULL,
  `socaude` int(11) DEFAULT NULL,
  `socautb` int(11) DEFAULT NULL,
  `socaukho` int(11) DEFAULT NULL,
  `trangthai` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dethi`
--

INSERT INTO `dethi` (`made`, `monthi`, `nguoitao`, `tende`, `thoigiantao`, `thoigianthi`, `thoigianbatdau`, `thoigianketthuc`, `hienthibailam`, `xemdiemthi`, `xemdapan`, `troncauhoi`, `trondapan`, `nopbaichuyentab`, `loaide`, `socaude`, `socautb`, `socaukho`, `trangthai`) VALUES
(1, 'LTW001', 'GVBM001', 'Kiểm tra lần 1', '2025-08-03 13:30:18', 20, '2025-08-03 00:00:00', '2025-08-10 12:00:00', 1, 1, 0, 0, 0, 0, 1, 2, 2, 2, 1),
(2, 'CSDL001', 'GVBM001', 'Kiểm tra lần 2', '2025-08-03 13:32:25', 20, '2025-08-03 00:00:00', '2025-08-04 12:00:00', 1, 1, 0, 0, 0, 0, 1, 5, 5, 5, 1),
(3, 'TTNT001', 'GVBM001', 'Kiểm tra lần 4', '2025-08-03 13:34:31', 18, '2025-08-03 00:00:00', '2025-08-07 12:00:00', 1, 1, 0, 0, 0, 0, 0, 5, 5, 5, 1),
(4, 'TTNT001', 'GVBM001', 'Thường Xuyên lần 5', '2025-08-03 14:05:51', 20, '2025-08-03 12:00:00', '2025-08-04 12:00:00', 1, 1, 0, 1, 1, 0, 0, 2, 2, 2, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `dethitudong`
--

CREATE TABLE `dethitudong` (
  `made` int(11) NOT NULL,
  `machuong` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `dethitudong`
--

INSERT INTO `dethitudong` (`made`, `machuong`) VALUES
(1, 1),
(1, 2),
(1, 3),
(1, 4),
(1, 5),
(1, 6),
(2, 7),
(2, 8),
(2, 9),
(2, 10),
(2, 11),
(2, 12),
(2, 13),
(3, 14),
(3, 15),
(3, 16),
(3, 17),
(3, 18),
(3, 19),
(3, 20);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giaodethi`
--

CREATE TABLE `giaodethi` (
  `made` int(11) NOT NULL,
  `manhom` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `giaodethi`
--

INSERT INTO `giaodethi` (`made`, `manhom`) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(4, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ketqua`
--

CREATE TABLE `ketqua` (
  `makq` int(11) NOT NULL,
  `made` int(11) NOT NULL,
  `manguoidung` varchar(50) NOT NULL DEFAULT '',
  `diemthi` double DEFAULT NULL,
  `thoigianvaothi` datetime DEFAULT current_timestamp(),
  `thoigianlambai` int(11) DEFAULT NULL,
  `socaudung` int(11) DEFAULT NULL,
  `solanchuyentab` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `monhoc`
--

CREATE TABLE `monhoc` (
  `mamonhoc` varchar(20) NOT NULL,
  `tenmonhoc` varchar(255) NOT NULL,
  `sotinchi` int(11) DEFAULT NULL,
  `sotietlythuyet` int(11) DEFAULT NULL,
  `sotietthuchanh` int(11) DEFAULT NULL,
  `trangthai` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `monhoc`
--

INSERT INTO `monhoc` (`mamonhoc`, `tenmonhoc`, `sotinchi`, `sotietlythuyet`, `sotietthuchanh`, `trangthai`) VALUES
('CSDL001', 'Cơ sở dữ liệu', 3, 30, 30, 1),
('LTDD001', 'Lập trình di động', 3, 30, 30, 1),
('LTW001', 'Lập trình web', 3, 30, 30, 1),
('PMMNM001', 'Phần mền mã nguồn mở', 2, 30, 0, 1),
('TTNT001', 'Trí tuệ nhân tạo', 2, 15, 30, 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nguoidung`
--

CREATE TABLE `nguoidung` (
  `email` varchar(255) NOT NULL,
  `id` varchar(50) NOT NULL,
  `googleid` varchar(150) DEFAULT NULL,
  `hoten` varchar(255) NOT NULL,
  `gioitinh` tinyint(1) DEFAULT NULL,
  `ngaysinh` date DEFAULT '1990-01-01',
  `avatar` varchar(255) DEFAULT NULL,
  `ngaythamgia` date NOT NULL DEFAULT current_timestamp(),
  `matkhau` varchar(60) DEFAULT NULL,
  `trangthai` int(11) NOT NULL,
  `sodienthoai` int(11) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `otp` varchar(10) DEFAULT NULL,
  `manhomquyen` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nguoidung`
--

INSERT INTO `nguoidung` (`email`, `id`, `googleid`, `hoten`, `gioitinh`, `ngaysinh`, `avatar`, `ngaythamgia`, `matkhau`, `trangthai`, `sodienthoai`, `token`, `otp`, `manhomquyen`) VALUES
('nvancntt2211001@dht.edu.vn', 'CNTT2211001', NULL, 'Nguyễn Văn An', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, '1754207358$2y$10$HZAFOkEeOayH2xXO623bTOIQPA5gmdSteJWxYhZpOI/PAohxj0.ju', NULL, 2),
('ttbichcntt2211002@dht.edu.vn', 'CNTT2211002', NULL, 'Trần Thị Bích', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lmchaucntt2211003@dht.edu.vn', 'CNTT2211003', NULL, 'Lê Minh Châu', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hp672341@dht.edu.vn', 'CNTT22110032', NULL, 'Hoàng', 0, '2004-02-04', NULL, '2025-08-03', '$2y$10$QNQRcfoVZTCPanap1VvQc.oS1B1fT5TeTJEeGQ68f3p0lOxERXP0C', 1, 333440700, NULL, '515355', 2),
('pqdcntt2211004@dht.edu.vn', 'CNTT2211004', NULL, 'Phạm Quốc Dũng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('htnhcntt2211005@dht.edu.vn', 'CNTT2211005', NULL, 'Hoàng Thị Ngọc Hà', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vvhcntt2211006@dht.edu.vn', 'CNTT2211006', NULL, 'Võ Văn Hùng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dtkimcntt2211007@dht.edu.vn', 'CNTT2211007', NULL, 'Đặng Thị Kim', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bmkhcntt2211008@dht.edu.vn', 'CNTT2211008', NULL, 'Bùi Minh Khang', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ntlcntt2211009@dht.edu.vn', 'CNTT2211009', NULL, 'Nguyễn Thị Lan', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tvlcntt2211010@dht.edu.vn', 'CNTT2211010', NULL, 'Trần Văn Long', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ltmcntt2211011@dht.edu.vn', 'CNTT2211011', NULL, 'Lê Thị Mai', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pvncntt2211012@dht.edu.vn', 'CNTT2211012', NULL, 'Phạm Văn Nam', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hmnhcntt2211013@dht.edu.vn', 'CNTT2211013', NULL, 'Hoàng Minh Nhật', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vtoanhcntt2211014@dht.edu.vn', 'CNTT2211014', NULL, 'Võ Thị Oanh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dvphcntt2211015@dht.edu.vn', 'CNTT2211015', NULL, 'Đặng Văn Phong', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('btphcntt2211016@dht.edu.vn', 'CNTT2211016', NULL, 'Bùi Thị Phương', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nqsncntt2211017@dht.edu.vn', 'CNTT2211017', NULL, 'Nguyễn Quốc Sơn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tthanhcntt2211018@dht.edu.vn', 'CNTT2211018', NULL, 'Trần Thị Thanh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvthanhcntt2211019@dht.edu.vn', 'CNTT2211019', NULL, 'Lê Văn Thành', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ptthaocntt2211020@dht.edu.vn', 'CNTT2211020', NULL, 'Phạm Thị Thảo', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvthcntt2211021@dht.edu.vn', 'CNTT2211021', NULL, 'Hoàng Văn Thiên', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vthucntt2211022@dht.edu.vn', 'CNTT2211022', NULL, 'Võ Thị Thu', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dmtricntt2211023@dht.edu.vn', 'CNTT2211023', NULL, 'Đặng Minh Trí', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bvtrcntt2211024@dht.edu.vn', 'CNTT2211024', NULL, 'Bùi Văn Trường', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nttamcntt2211025@dht.edu.vn', 'CNTT2211025', NULL, 'Nguyễn Thị Tâm', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tvtungcntt2211026@dht.edu.vn', 'CNTT2211026', NULL, 'Trần Văn Tùng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvcntt2211027@dht.edu.vn', 'CNTT2211027', NULL, 'Lê Thị Vân', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pvvcntt2211028@dht.edu.vn', 'CNTT2211028', NULL, 'Phạm Văn Vinh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('htxcntt2211029@dht.edu.vn', 'CNTT2211029', NULL, 'Hoàng Thị Xuân', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vmycntt2211030@dht.edu.vn', 'CNTT2211030', NULL, 'Võ Minh Ý', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dtycntt2211031@dht.edu.vn', 'CNTT2211031', NULL, 'Đặng Thị Yến', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('gvbm001@dht.edu.vn', 'GVBM001', NULL, 'Phạm Sơn Hoàng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, '1754207051$2y$10$A5LiUY7ueFYepQd1v.lMc.Zh23gDUAFJJjCcGcbKBdLVM6nyG6G3i', NULL, 1),
('gvbm002@dht.edu.vn', 'GVBM002', NULL, 'Liên Hòa Thuận', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 1),
('nvbhttt2211001@dht.edu.vn', 'HTTT2211001', NULL, 'Nguyễn Văn Bình', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tthcntt2211002@dht.edu.vn', 'HTTT2211002', NULL, 'Trần Thị Hồng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvdcntt2211003@dht.edu.vn', 'HTTT2211003', NULL, 'Lê Văn Dũng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ptlcntt2211004@dht.edu.vn', 'HTTT2211004', NULL, 'Phạm Thị Linh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvkhcntt2211005@dht.edu.vn', 'HTTT2211005', NULL, 'Hoàng Văn Khánh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vtmcntt2211006@dht.edu.vn', 'HTTT2211006', NULL, 'Võ Thị Minh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dtngcntt2211007@dht.edu.vn', 'HTTT2211007', NULL, 'Đặng Thị Ngân', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bvphttt2211008@dht.edu.vn', 'HTTT2211008', NULL, 'Bùi Văn Phú', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ntqhttt2211009@dht.edu.vn', 'HTTT2211009', NULL, 'Nguyễn Thị Quyên', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tvshttt2211010@dht.edu.vn', 'HTTT2211010', NULL, 'Trần Văn Sang', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lttcntt2211011@dht.edu.vn', 'HTTT2211011', NULL, 'Lê Thị Thùy', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pvthcntt2211012@dht.edu.vn', 'HTTT2211012', NULL, 'Phạm Văn Thịnh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('htthcntt2211013@dht.edu.vn', 'HTTT2211013', NULL, 'Hoàng Thị Thơ', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vvthcntt2211014@dht.edu.vn', 'HTTT2211014', NULL, 'Võ Văn Thắng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dvtcntt2211015@dht.edu.vn', 'HTTT2211015', NULL, 'Đặng Văn Tâm', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('btthcntt2211016@dht.edu.vn', 'HTTT2211016', NULL, 'Bùi Thị Thắm', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nvtrcntt2211017@dht.edu.vn', 'HTTT2211017', NULL, 'Nguyễn Văn Trung', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ttvhttt2211018@dht.edu.vn', 'HTTT2211018', NULL, 'Trần Thị Vân', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvvhttt2211019@dht.edu.vn', 'HTTT2211019', NULL, 'Lê Văn Vũ', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ptycntt2211020@dht.edu.vn', 'HTTT2211020', NULL, 'Phạm Thị Yến', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvdcntt2211021@dht.edu.vn', 'HTTT2211021', NULL, 'Hoàng Văn Đức', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vthcntt2211022@dht.edu.vn', 'HTTT2211022', NULL, 'Võ Thị Hạnh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dmkhttt2211023@dht.edu.vn', 'HTTT2211023', NULL, 'Đặng Minh Kiên', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bvncntt2211024@dht.edu.vn', 'HTTT2211024', NULL, 'Bùi Văn Nguyên', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ntnhttt2211025@dht.edu.vn', 'HTTT2211025', NULL, 'Nguyễn Thị Nhung', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tvphttt2211026@dht.edu.vn', 'HTTT2211026', NULL, 'Trần Văn Phúc', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lttcntt2211027@dht.edu.vn', 'HTTT2211027', NULL, 'Lê Thị Trang', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pvthcntt2211028@dht.edu.vn', 'HTTT2211028', NULL, 'Phạm Văn Thái', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('httcntt2211029@dht.edu.vn', 'HTTT2211029', NULL, 'Hoàng Thị Trinh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vvthcntt2211030@dht.edu.vn', 'HTTT2211030', NULL, 'Võ Văn Thông', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dthcntt2211031@dht.edu.vn', 'HTTT2211031', NULL, 'Đặng Thị Hương', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nvckhdl2211001@dht.edu.vn', 'KHDL2211001', NULL, 'Nguyễn Văn Cường', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ttkhd2211002@dht.edu.vn', 'KHDL2211002', NULL, 'Trần Thị Kiều', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvnkhdl2211003@dht.edu.vn', 'KHDL2211003', NULL, 'Lê Văn Nam', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pttkhdl2211004@dht.edu.vn', 'KHDL2211004', NULL, 'Phạm Thị Tâm', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvkhd2211005@dht.edu.vn', 'KHDL2211005', NULL, 'Hoàng Văn Kiên', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vtnkhdl2211006@dht.edu.vn', 'KHDL2211006', NULL, 'Võ Thị Nga', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dtpkhdl2211007@dht.edu.vn', 'KHDL2211007', NULL, 'Đặng Thị Phượng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bvqkhdl2211008@dht.edu.vn', 'KHDL2211008', NULL, 'Bùi Văn Quang', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ntthkhdl2211009@dht.edu.vn', 'KHDL2211009', NULL, 'Nguyễn Thị Thảo', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tvtkhdl2211010@dht.edu.vn', 'KHDL2211010', NULL, 'Trần Văn Tài', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ltnkhdl2211011@dht.edu.vn', 'KHDL2211011', NULL, 'Lê Thị Ngọc', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pvtkhdl2211012@dht.edu.vn', 'KHDL2211012', NULL, 'Phạm Văn Tuấn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('htvkhdl2211013@dht.edu.vn', 'KHDL2211013', NULL, 'Hoàng Thị Vân', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vvpkhdl2211014@dht.edu.vn', 'KHDL2211014', NULL, 'Võ Văn Phúc', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dvtkhdl2211015@dht.edu.vn', 'KHDL2211015', NULL, 'Đặng Văn Tín', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('btthkhdl2211016@dht.edu.vn', 'KHDL2211016', NULL, 'Bùi Thị Thanh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nvtkhdl2211017@dht.edu.vn', 'KHDL2211017', NULL, 'Nguyễn Văn Thắng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tttkhdl2211018@dht.edu.vn', 'KHDL2211018', NULL, 'Trần Thị Trang', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvtkhdl2211019@dht.edu.vn', 'KHDL2211019', NULL, 'Lê Văn Tùng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ptnkhdl2211020@dht.edu.vn', 'KHDL2211020', NULL, 'Phạm Thị Nhàn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvthkhdl2211021@dht.edu.vn', 'KHDL2211021', NULL, 'Hoàng Văn Thành', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vthkhdl2211022@dht.edu.vn', 'KHDL2211022', NULL, 'Võ Thị Hoa', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dmnkhdl2211023@dht.edu.vn', 'KHDL2211023', NULL, 'Đặng Minh Nghĩa', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bvtkhdl2211024@dht.edu.vn', 'KHDL2211024', NULL, 'Bùi Văn Tiến', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nttkhdl2211025@dht.edu.vn', 'KHDL2211025', NULL, 'Nguyễn Thị Thúy', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tvtkhdl2211026@dht.edu.vn', 'KHDL2211026', NULL, 'Trần Văn Trung', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lthkhdl2211027@dht.edu.vn', 'KHDL2211027', NULL, 'Lê Thị Hằng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pvtkhdl2211028@dht.edu.vn', 'KHDL2211028', NULL, 'Phạm Văn Toàn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('htnkhdl2211029@dht.edu.vn', 'KHDL2211029', NULL, 'Hoàng Thị Ngọc', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vvtkhdl2211030@dht.edu.vn', 'KHDL2211030', NULL, 'Võ Văn Tâm', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dtnkhdl2211031@dht.edu.vn', 'KHDL2211031', NULL, 'Đặng Thị Nhi', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nvhkhmt2211001@dht.edu.vn', 'KHMT2211001', NULL, 'Nguyễn Văn Hùng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ttlkhmt2211002@dht.edu.vn', 'KHMT2211002', NULL, 'Trần Thị Lan', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lmqkhmt2211003@dht.edu.vn', 'KHMT2211003', NULL, 'Lê Minh Quang', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pthkhmt2211004@dht.edu.vn', 'KHMT2211004', NULL, 'Phạm Thị Hương', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvbkhmt2211005@dht.edu.vn', 'KHMT2211005', NULL, 'Hoàng Văn Bảo', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vttkhmt2211006@dht.edu.vn', 'KHMT2211006', NULL, 'Võ Thị Thảo', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dvlkhmt2211007@dht.edu.vn', 'KHMT2211007', NULL, 'Đặng Văn Long', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('btmkhmt2211008@dht.edu.vn', 'KHMT2211008', NULL, 'Bùi Thị Mai', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nvpkhmt2211009@dht.edu.vn', 'KHMT2211009', NULL, 'Nguyễn Văn Phúc', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ttnkhmt2211010@dht.edu.vn', 'KHMT2211010', NULL, 'Trần Thị Ngọc', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvkkhmt2211011@dht.edu.vn', 'KHMT2211011', NULL, 'Lê Văn Khánh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pthkhmt2211012@dht.edu.vn', 'KHMT2211012', NULL, 'Phạm Thị Hồng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvtkhmt2211013@dht.edu.vn', 'KHMT2211013', NULL, 'Hoàng Văn Tâm', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vtvkhmt2211014@dht.edu.vn', 'KHMT2211014', NULL, 'Võ Thị Vân', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dmtkhmt2211015@dht.edu.vn', 'KHMT2211015', NULL, 'Đặng Minh Tuấn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bttkhmt2211016@dht.edu.vn', 'KHMT2211016', NULL, 'Bùi Thị Thùy', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nvtthkhmt2211017@dht.edu.vn', 'KHMT2211017', NULL, 'Nguyễn Văn Thắng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tttkhmt2211018@dht.edu.vn', 'KHMT2211018', NULL, 'Trần Thị Thanh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvthkhmt2211019@dht.edu.vn', 'KHMT2211019', NULL, 'Lê Văn Thành', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ptnkhmt2211020@dht.edu.vn', 'KHMT2211020', NULL, 'Phạm Thị Nhung', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvtikhmt2211021@dht.edu.vn', 'KHMT2211021', NULL, 'Hoàng Văn Tiến', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vthkhmt2211022@dht.edu.vn', 'KHMT2211022', NULL, 'Võ Thị Hiền', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dmnkhmt2211023@dht.edu.vn', 'KHMT2211023', NULL, 'Đặng Minh Nhật', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bvtikhmt2211024@dht.edu.vn', 'KHMT2211024', NULL, 'Bùi Văn Tín', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nttthkhmt2211025@dht.edu.vn', 'KHMT2211025', NULL, 'Nguyễn Thị Trang', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tvtkhmt2211026@dht.edu.vn', 'KHMT2211026', NULL, 'Trần Văn Toàn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ltnkhmt2211027@dht.edu.vn', 'KHMT2211027', NULL, 'Lê Thị Nga', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pvthkhmt2211028@dht.edu.vn', 'KHMT2211028', NULL, 'Phạm Văn Thái', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('htnkhmt2211029@dht.edu.vn', 'KHMT2211029', NULL, 'Hoàng Thị Nhàn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vvtthkhmt2211030@dht.edu.vn', 'KHMT2211030', NULL, 'Võ Văn Tùng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dttkhmt2211031@dht.edu.vn', 'KHMT2211031', NULL, 'Đặng Thị Thúy', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nvdkhpm2211001@dht.edu.vn', 'KTPM2211001', NULL, 'Nguyễn Văn Đạt', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, '1754207035$2y$10$JbhBd7DeWmDb5c4Yu/NXfOwT4AaXq86wjfoCuLO.alYuHaj/GJCzu', NULL, 2),
('ttnktpm2211002@dht.edu.vn', 'KTPM2211002', NULL, 'Trần Thị Nhung', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvthktpm2211003@dht.edu.vn', 'KTPM2211003', NULL, 'Lê Văn Thành', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ptthktpm2211004@dht.edu.vn', 'KTPM2211004', NULL, 'Phạm Thị Thủy', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvtrktpm2211005@dht.edu.vn', 'KTPM2211005', NULL, 'Hoàng Văn Trí', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vtqktpm2211006@dht.edu.vn', 'KTPM2211006', NULL, 'Võ Thị Quyên', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dtnktpm2211007@dht.edu.vn', 'KTPM2211007', NULL, 'Đặng Thị Nhi', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bvhktpm2211008@dht.edu.vn', 'KTPM2211008', NULL, 'Bùi Văn Hòa', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ntvktpm2211009@dht.edu.vn', 'KTPM2211009', NULL, 'Nguyễn Thị Vân', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tvthktpm2211010@dht.edu.vn', 'KTPM2211010', NULL, 'Trần Văn Thành', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lthktpm2211011@dht.edu.vn', 'KTPM2211011', NULL, 'Lê Thị Hồng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pvthktpm2211012@dht.edu.vn', 'KTPM2211012', NULL, 'Phạm Văn Thắng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('htthktpm2211013@dht.edu.vn', 'KTPM2211013', NULL, 'Hoàng Thị Thảo', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vvtkhpm2211014@dht.edu.vn', 'KTPM2211014', NULL, 'Võ Văn Tùng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dvthktpm2211015@dht.edu.vn', 'KTPM2211015', NULL, 'Đặng Văn Thiện', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('btthktpm2211016@dht.edu.vn', 'KTPM2211016', NULL, 'Bùi Thị Thùy', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nvtkhpm2211017@dht.edu.vn', 'KTPM2211017', NULL, 'Nguyễn Văn Tuấn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tttkhpm2211018@dht.edu.vn', 'KTPM2211018', NULL, 'Trần Thị Thanh', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lvtkhpm2211019@dht.edu.vn', 'KTPM2211019', NULL, 'Lê Văn Thái', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('ptnktpm2211020@dht.edu.vn', 'KTPM2211020', NULL, 'Phạm Thị Nhung', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('hvtkhpm2211021@dht.edu.vn', 'KTPM2211021', NULL, 'Hoàng Văn Tâm', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vthktpm2211022@dht.edu.vn', 'KTPM2211022', NULL, 'Võ Thị Hiền', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dmnktpm2211023@dht.edu.vn', 'KTPM2211023', NULL, 'Đặng Minh Nhật', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('bvtkhpm2211024@dht.edu.vn', 'KTPM2211024', NULL, 'Bùi Văn Tín', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('nttkhpm2211025@dht.edu.vn', 'KTPM2211025', NULL, 'Nguyễn Thị Trang', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('tvtkhpm2211026@dht.edu.vn', 'KTPM2211026', NULL, 'Trần Văn Thắng', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('lthktpm2211027@dht.edu.vn', 'KTPM2211027', NULL, 'Lê Thị Hương', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('pvtkhpm2211028@dht.edu.vn', 'KTPM2211028', NULL, 'Phạm Văn Toàn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('htnktpm2211029@dht.edu.vn', 'KTPM2211029', NULL, 'Hoàng Thị Nga', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('vvtkhpm2211030@dht.edu.vn', 'KTPM2211030', NULL, 'Võ Văn Thành', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2),
('dtnktpm2211031@dht.edu.vn', 'KTPM2211031', NULL, 'Đặng Thị Nhàn', NULL, '1990-01-01', NULL, '2025-08-01', '$2y$10$TxtprnFkTCQ7jlTW5S2H5.ovnMIGWOIqcqT.QSFRVUWK9Dbi7i6Bq', 1, NULL, NULL, NULL, 2);

--
-- Bẫy `nguoidung`
--
DELIMITER $$
CREATE TRIGGER `delete_chitietnhom_by_id` BEFORE DELETE ON `nguoidung` FOR EACH ROW DELETE FROM chitietnhom WHERE chitietnhom.manguoidung = OLD.id
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhom`
--

CREATE TABLE `nhom` (
  `manhom` int(11) NOT NULL,
  `tennhom` varchar(255) NOT NULL,
  `mamoi` varchar(50) DEFAULT NULL,
  `siso` int(11) DEFAULT 0,
  `ghichu` varchar(255) DEFAULT NULL,
  `namhoc` int(11) DEFAULT NULL,
  `hocky` int(11) DEFAULT NULL,
  `trangthai` tinyint(1) DEFAULT 1,
  `hienthi` tinyint(1) DEFAULT 1,
  `giangvien` varchar(50) NOT NULL DEFAULT '',
  `mamonhoc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhom`
--

INSERT INTO `nhom` (`manhom`, `tennhom`, `mamoi`, `siso`, `ghichu`, `namhoc`, `hocky`, `trangthai`, `hienthi`, `giangvien`, `mamonhoc`) VALUES
(1, 'LTW_CNTT2211', 'e4c2940', 32, 'Tiết 1-3 sáng thứ 3', 2025, 1, 1, 1, 'GVBM001', 'LTW001'),
(2, 'LTW_HTTT2211', 'fbd7c06', 0, 'Tiết 1-3 sáng thứ 2', 2025, 1, 1, 1, 'GVBM001', 'LTW001'),
(3, 'CSDL_CNTT2211', '20889a2', 32, 'Tiết 1-3 sáng thứ 5', 2025, 1, 1, 1, 'GVBM001', 'CSDL001'),
(4, 'CSDL_HTTT2211', '20ef394', 1, 'Tiết 6-8 chiều T3', 2025, 1, 1, 1, 'GVBM001', 'CSDL001'),
(5, 'TTNT001_KTPM2211', 'fc264b6', 31, 'Tiết 1-3 sáng thứ 4', 2025, 1, 1, 1, 'GVBM001', 'TTNT001');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhomquyen`
--

CREATE TABLE `nhomquyen` (
  `manhomquyen` int(11) NOT NULL,
  `tennhomquyen` varchar(50) NOT NULL,
  `trangthai` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `nhomquyen`
--

INSERT INTO `nhomquyen` (`manhomquyen`, `tennhomquyen`, `trangthai`) VALUES
(1, 'Giáo Viên', 1),
(2, 'Sinh Viên', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phancong`
--

CREATE TABLE `phancong` (
  `mamonhoc` varchar(20) NOT NULL,
  `manguoidung` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `phancong`
--

INSERT INTO `phancong` (`mamonhoc`, `manguoidung`) VALUES
('CSDL001', 'GVBM001'),
('LTDD001', 'GVBM001'),
('LTW001', 'GVBM001'),
('PMMNM001', 'GVBM001'),
('TTNT001', 'GVBM001');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `thongbao`
--

CREATE TABLE `thongbao` (
  `matb` int(11) NOT NULL,
  `noidung` varchar(255) DEFAULT NULL,
  `thoigiantao` datetime DEFAULT NULL,
  `nguoitao` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `thongbao`
--

INSERT INTO `thongbao` (`matb`, `noidung`, `thoigiantao`, `nguoitao`) VALUES
(1, '<span style=\"text-decoration: underline;\">Đề thi mới: Kiểm tra lần 1 – Môn LTW001</span>', '2025-08-03 08:30:18', 'GVBM001'),
(2, '<span style=\"text-decoration: underline;\">Đề thi mới: Kiểm tra lần 2 – Môn CSDL001</span>', '2025-08-03 08:32:25', 'GVBM001'),
(3, '<span style=\"text-decoration: underline;\">Đề thi mới: Kiểm tra lần 4 – Môn TTNT001</span>', '2025-08-03 08:34:31', 'GVBM001'),
(4, '<span style=\"text-decoration: underline;\">Đề thi mới: Thường Xuyên lần 5 – Môn TTNT001</span>', '2025-08-03 09:05:51', 'GVBM001');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `trangthaithongbao`
--

CREATE TABLE `trangthaithongbao` (
  `matb` int(11) NOT NULL,
  `manguoidung` varchar(50) NOT NULL,
  `trangthai` varchar(20) DEFAULT 'chưa xem'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `trangthaithongbao`
--

INSERT INTO `trangthaithongbao` (`matb`, `manguoidung`, `trangthai`) VALUES
(1, 'CNTT2211001', 'chưa xem'),
(1, 'CNTT2211002', 'chưa xem'),
(1, 'CNTT2211003', 'chưa xem'),
(1, 'CNTT22110032', 'chưa xem'),
(1, 'CNTT2211004', 'chưa xem'),
(1, 'CNTT2211005', 'chưa xem'),
(1, 'CNTT2211006', 'chưa xem'),
(1, 'CNTT2211007', 'chưa xem'),
(1, 'CNTT2211008', 'chưa xem'),
(1, 'CNTT2211009', 'chưa xem'),
(1, 'CNTT2211010', 'chưa xem'),
(1, 'CNTT2211011', 'chưa xem'),
(1, 'CNTT2211012', 'chưa xem'),
(1, 'CNTT2211013', 'chưa xem'),
(1, 'CNTT2211014', 'chưa xem'),
(1, 'CNTT2211015', 'chưa xem'),
(1, 'CNTT2211016', 'chưa xem'),
(1, 'CNTT2211017', 'chưa xem'),
(1, 'CNTT2211018', 'chưa xem'),
(1, 'CNTT2211019', 'chưa xem'),
(1, 'CNTT2211020', 'chưa xem'),
(1, 'CNTT2211021', 'chưa xem'),
(1, 'CNTT2211022', 'chưa xem'),
(1, 'CNTT2211023', 'chưa xem'),
(1, 'CNTT2211024', 'chưa xem'),
(1, 'CNTT2211025', 'chưa xem'),
(1, 'CNTT2211026', 'chưa xem'),
(1, 'CNTT2211027', 'chưa xem'),
(1, 'CNTT2211028', 'chưa xem'),
(1, 'CNTT2211029', 'chưa xem'),
(1, 'CNTT2211030', 'chưa xem'),
(1, 'CNTT2211031', 'chưa xem'),
(2, 'CNTT2211001', 'chưa xem'),
(2, 'CNTT2211002', 'chưa xem'),
(2, 'CNTT2211003', 'chưa xem'),
(2, 'CNTT22110032', 'chưa xem'),
(2, 'CNTT2211004', 'chưa xem'),
(2, 'CNTT2211005', 'chưa xem'),
(2, 'CNTT2211006', 'chưa xem'),
(2, 'CNTT2211007', 'chưa xem'),
(2, 'CNTT2211008', 'chưa xem'),
(2, 'CNTT2211009', 'chưa xem'),
(2, 'CNTT2211010', 'chưa xem'),
(2, 'CNTT2211011', 'chưa xem'),
(2, 'CNTT2211012', 'chưa xem'),
(2, 'CNTT2211013', 'chưa xem'),
(2, 'CNTT2211014', 'chưa xem'),
(2, 'CNTT2211015', 'chưa xem'),
(2, 'CNTT2211016', 'chưa xem'),
(2, 'CNTT2211017', 'chưa xem'),
(2, 'CNTT2211018', 'chưa xem'),
(2, 'CNTT2211019', 'chưa xem'),
(2, 'CNTT2211020', 'chưa xem'),
(2, 'CNTT2211021', 'chưa xem'),
(2, 'CNTT2211022', 'chưa xem'),
(2, 'CNTT2211023', 'chưa xem'),
(2, 'CNTT2211024', 'chưa xem'),
(2, 'CNTT2211025', 'chưa xem'),
(2, 'CNTT2211026', 'chưa xem'),
(2, 'CNTT2211027', 'chưa xem'),
(2, 'CNTT2211028', 'chưa xem'),
(2, 'CNTT2211029', 'chưa xem'),
(2, 'CNTT2211030', 'chưa xem'),
(2, 'CNTT2211031', 'chưa xem'),
(2, 'HTTT2211003', 'chưa xem'),
(3, 'KTPM2211001', 'chưa xem'),
(3, 'KTPM2211002', 'chưa xem'),
(3, 'KTPM2211003', 'chưa xem'),
(3, 'KTPM2211004', 'chưa xem'),
(3, 'KTPM2211005', 'chưa xem'),
(3, 'KTPM2211006', 'chưa xem'),
(3, 'KTPM2211007', 'chưa xem'),
(3, 'KTPM2211008', 'chưa xem'),
(3, 'KTPM2211009', 'chưa xem'),
(3, 'KTPM2211010', 'chưa xem'),
(3, 'KTPM2211011', 'chưa xem'),
(3, 'KTPM2211012', 'chưa xem'),
(3, 'KTPM2211013', 'chưa xem'),
(3, 'KTPM2211014', 'chưa xem'),
(3, 'KTPM2211015', 'chưa xem'),
(3, 'KTPM2211016', 'chưa xem'),
(3, 'KTPM2211017', 'chưa xem'),
(3, 'KTPM2211018', 'chưa xem'),
(3, 'KTPM2211019', 'chưa xem'),
(3, 'KTPM2211020', 'chưa xem'),
(3, 'KTPM2211021', 'chưa xem'),
(3, 'KTPM2211022', 'chưa xem'),
(3, 'KTPM2211023', 'chưa xem'),
(3, 'KTPM2211024', 'chưa xem'),
(3, 'KTPM2211025', 'chưa xem'),
(3, 'KTPM2211026', 'chưa xem'),
(3, 'KTPM2211027', 'chưa xem'),
(3, 'KTPM2211028', 'chưa xem'),
(3, 'KTPM2211029', 'chưa xem'),
(3, 'KTPM2211030', 'chưa xem'),
(3, 'KTPM2211031', 'chưa xem'),
(4, 'KTPM2211001', 'chưa xem'),
(4, 'KTPM2211002', 'chưa xem'),
(4, 'KTPM2211003', 'chưa xem'),
(4, 'KTPM2211004', 'chưa xem'),
(4, 'KTPM2211005', 'chưa xem'),
(4, 'KTPM2211006', 'chưa xem'),
(4, 'KTPM2211007', 'chưa xem'),
(4, 'KTPM2211008', 'chưa xem'),
(4, 'KTPM2211009', 'chưa xem'),
(4, 'KTPM2211010', 'chưa xem'),
(4, 'KTPM2211011', 'chưa xem'),
(4, 'KTPM2211012', 'chưa xem'),
(4, 'KTPM2211013', 'chưa xem'),
(4, 'KTPM2211014', 'chưa xem'),
(4, 'KTPM2211015', 'chưa xem'),
(4, 'KTPM2211016', 'chưa xem'),
(4, 'KTPM2211017', 'chưa xem'),
(4, 'KTPM2211018', 'chưa xem'),
(4, 'KTPM2211019', 'chưa xem'),
(4, 'KTPM2211020', 'chưa xem'),
(4, 'KTPM2211021', 'chưa xem'),
(4, 'KTPM2211022', 'chưa xem'),
(4, 'KTPM2211023', 'chưa xem'),
(4, 'KTPM2211024', 'chưa xem'),
(4, 'KTPM2211025', 'chưa xem'),
(4, 'KTPM2211026', 'chưa xem'),
(4, 'KTPM2211027', 'chưa xem'),
(4, 'KTPM2211028', 'chưa xem'),
(4, 'KTPM2211029', 'chưa xem'),
(4, 'KTPM2211030', 'chưa xem'),
(4, 'KTPM2211031', 'chưa xem');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cauhoi`
--
ALTER TABLE `cauhoi`
  ADD PRIMARY KEY (`macauhoi`),
  ADD KEY `FK_CAUHOI_NGUOIDUNG` (`nguoitao`),
  ADD KEY `FK_CAUHOI_CHUONG` (`machuong`),
  ADD KEY `FK_CAUHOI_MONHOC` (`mamonhoc`);

--
-- Chỉ mục cho bảng `cautraloi`
--
ALTER TABLE `cautraloi`
  ADD PRIMARY KEY (`macautl`),
  ADD KEY `FK_CAUTRALOI_CAUHOI` (`macauhoi`);

--
-- Chỉ mục cho bảng `chitietdethi`
--
ALTER TABLE `chitietdethi`
  ADD PRIMARY KEY (`made`,`macauhoi`),
  ADD KEY `FK_CHITIETDETHI_CAUHOI` (`macauhoi`);

--
-- Chỉ mục cho bảng `chitietketqua`
--
ALTER TABLE `chitietketqua`
  ADD PRIMARY KEY (`makq`,`macauhoi`),
  ADD KEY `FK_CHITIETKETQUA_CAUHOI` (`macauhoi`),
  ADD KEY `FK_CHITIETKETQUA_CAUTRALOI` (`dapanchon`);

--
-- Chỉ mục cho bảng `chitietnhom`
--
ALTER TABLE `chitietnhom`
  ADD PRIMARY KEY (`manhom`,`manguoidung`),
  ADD KEY `FK_CHITIETNHOM_NGUOIDUNG` (`manguoidung`);

--
-- Chỉ mục cho bảng `chitietquyen`
--
ALTER TABLE `chitietquyen`
  ADD PRIMARY KEY (`manhomquyen`,`chucnang`,`hanhdong`) USING BTREE,
  ADD KEY `hanhdong` (`chucnang`) USING BTREE;

--
-- Chỉ mục cho bảng `chitietthongbao`
--
ALTER TABLE `chitietthongbao`
  ADD PRIMARY KEY (`matb`,`manhom`),
  ADD KEY `FK_CHITIETTHONGBAO_NHOM` (`manhom`);

--
-- Chỉ mục cho bảng `chuong`
--
ALTER TABLE `chuong`
  ADD PRIMARY KEY (`machuong`),
  ADD KEY `FK_CHUONG_MONHOC` (`mamonhoc`);

--
-- Chỉ mục cho bảng `danhmucchucnang`
--
ALTER TABLE `danhmucchucnang`
  ADD PRIMARY KEY (`chucnang`) USING BTREE;

--
-- Chỉ mục cho bảng `dethi`
--
ALTER TABLE `dethi`
  ADD PRIMARY KEY (`made`),
  ADD KEY `fk_dethi_monhoc` (`monthi`);

--
-- Chỉ mục cho bảng `dethitudong`
--
ALTER TABLE `dethitudong`
  ADD PRIMARY KEY (`made`,`machuong`),
  ADD KEY `FK_DETHITUDONG_CHUONG` (`machuong`);

--
-- Chỉ mục cho bảng `giaodethi`
--
ALTER TABLE `giaodethi`
  ADD PRIMARY KEY (`made`,`manhom`),
  ADD KEY `FK_GIAODETHI_NHOM` (`manhom`);

--
-- Chỉ mục cho bảng `ketqua`
--
ALTER TABLE `ketqua`
  ADD PRIMARY KEY (`made`,`manguoidung`),
  ADD UNIQUE KEY `stt` (`makq`) USING BTREE,
  ADD KEY `FK_KETQUA_NGUOIDUNG` (`manguoidung`);

--
-- Chỉ mục cho bảng `monhoc`
--
ALTER TABLE `monhoc`
  ADD PRIMARY KEY (`mamonhoc`);

--
-- Chỉ mục cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_NGUOIDUNG_NHOMQUYEN` (`manhomquyen`);

--
-- Chỉ mục cho bảng `nhom`
--
ALTER TABLE `nhom`
  ADD PRIMARY KEY (`manhom`),
  ADD KEY `FK_NHOM_NGUOIDUNG` (`giangvien`),
  ADD KEY `FK_nhom_monhoc` (`mamonhoc`);

--
-- Chỉ mục cho bảng `nhomquyen`
--
ALTER TABLE `nhomquyen`
  ADD PRIMARY KEY (`manhomquyen`);

--
-- Chỉ mục cho bảng `phancong`
--
ALTER TABLE `phancong`
  ADD PRIMARY KEY (`mamonhoc`,`manguoidung`),
  ADD KEY `FK_giangday_nguoidung` (`manguoidung`);

--
-- Chỉ mục cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  ADD PRIMARY KEY (`matb`),
  ADD KEY `FK_THONGBAO_NGUOIDUNG` (`nguoitao`);

--
-- Chỉ mục cho bảng `trangthaithongbao`
--
ALTER TABLE `trangthaithongbao`
  ADD PRIMARY KEY (`matb`,`manguoidung`),
  ADD KEY `manguoidung` (`manguoidung`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cauhoi`
--
ALTER TABLE `cauhoi`
  MODIFY `macauhoi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=378;

--
-- AUTO_INCREMENT cho bảng `cautraloi`
--
ALTER TABLE `cautraloi`
  MODIFY `macautl` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1513;

--
-- AUTO_INCREMENT cho bảng `chuong`
--
ALTER TABLE `chuong`
  MODIFY `machuong` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `dethi`
--
ALTER TABLE `dethi`
  MODIFY `made` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `ketqua`
--
ALTER TABLE `ketqua`
  MODIFY `makq` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `nhom`
--
ALTER TABLE `nhom`
  MODIFY `manhom` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `nhomquyen`
--
ALTER TABLE `nhomquyen`
  MODIFY `manhomquyen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `thongbao`
--
ALTER TABLE `thongbao`
  MODIFY `matb` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cauhoi`
--
ALTER TABLE `cauhoi`
  ADD CONSTRAINT `FK_CAUHOI_CHUONG` FOREIGN KEY (`machuong`) REFERENCES `chuong` (`machuong`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_CAUHOI_MONHOC` FOREIGN KEY (`mamonhoc`) REFERENCES `monhoc` (`mamonhoc`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `cautraloi`
--
ALTER TABLE `cautraloi`
  ADD CONSTRAINT `FK_CAUTRALOI_CAUHOI` FOREIGN KEY (`macauhoi`) REFERENCES `cauhoi` (`macauhoi`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `chitietdethi`
--
ALTER TABLE `chitietdethi`
  ADD CONSTRAINT `FK_CHITIETDETHI_CAUHOI` FOREIGN KEY (`macauhoi`) REFERENCES `cauhoi` (`macauhoi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_CHITIETDETHI_DETHI` FOREIGN KEY (`made`) REFERENCES `dethi` (`made`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `chitietketqua`
--
ALTER TABLE `chitietketqua`
  ADD CONSTRAINT `FK_CHITIETKETQUA_CAUHOI` FOREIGN KEY (`macauhoi`) REFERENCES `cauhoi` (`macauhoi`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_CHITIETKETQUA_CAUTRALOI` FOREIGN KEY (`dapanchon`) REFERENCES `cautraloi` (`macautl`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_CHITIETKETQUA_KETQUA` FOREIGN KEY (`makq`) REFERENCES `ketqua` (`makq`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `chitietnhom`
--
ALTER TABLE `chitietnhom`
  ADD CONSTRAINT `FK_CHITIETNHOM_NHOM` FOREIGN KEY (`manhom`) REFERENCES `nhom` (`manhom`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_chitietnhom_nguoidung` FOREIGN KEY (`manguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `chitietquyen`
--
ALTER TABLE `chitietquyen`
  ADD CONSTRAINT `FK_CHITIETQUYEN_NHOMQUYEN` FOREIGN KEY (`manhomquyen`) REFERENCES `nhomquyen` (`manhomquyen`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `chitietquyen_ibfk_1` FOREIGN KEY (`chucnang`) REFERENCES `danhmucchucnang` (`chucnang`);

--
-- Các ràng buộc cho bảng `chitietthongbao`
--
ALTER TABLE `chitietthongbao`
  ADD CONSTRAINT `FK_CHITIETTHONGBAO_NHOM` FOREIGN KEY (`manhom`) REFERENCES `nhom` (`manhom`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_CHITIETTHONGBAO_THONGBAO` FOREIGN KEY (`matb`) REFERENCES `thongbao` (`matb`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `chuong`
--
ALTER TABLE `chuong`
  ADD CONSTRAINT `FK_CHUONG_MONHOC` FOREIGN KEY (`mamonhoc`) REFERENCES `monhoc` (`mamonhoc`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `dethi`
--
ALTER TABLE `dethi`
  ADD CONSTRAINT `fk_dethi_monhoc` FOREIGN KEY (`monthi`) REFERENCES `monhoc` (`mamonhoc`) ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `dethitudong`
--
ALTER TABLE `dethitudong`
  ADD CONSTRAINT `FK_DETHITUDONG_CHUONG` FOREIGN KEY (`machuong`) REFERENCES `chuong` (`machuong`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_DETHITUDONG_DETHI` FOREIGN KEY (`made`) REFERENCES `dethi` (`made`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `giaodethi`
--
ALTER TABLE `giaodethi`
  ADD CONSTRAINT `FK_GIAODETHI_DETHI` FOREIGN KEY (`made`) REFERENCES `dethi` (`made`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_GIAODETHI_NHOM` FOREIGN KEY (`manhom`) REFERENCES `nhom` (`manhom`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `ketqua`
--
ALTER TABLE `ketqua`
  ADD CONSTRAINT `FK_KETQUA_DETHI` FOREIGN KEY (`made`) REFERENCES `dethi` (`made`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_ketqua_nguoidung` FOREIGN KEY (`manguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `nguoidung`
--
ALTER TABLE `nguoidung`
  ADD CONSTRAINT `FK_NGUOIDUNG_NHOMQUYEN` FOREIGN KEY (`manhomquyen`) REFERENCES `nhomquyen` (`manhomquyen`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `nhom`
--
ALTER TABLE `nhom`
  ADD CONSTRAINT `FK_nhom_monhoc` FOREIGN KEY (`mamonhoc`) REFERENCES `monhoc` (`mamonhoc`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `phancong`
--
ALTER TABLE `phancong`
  ADD CONSTRAINT `FK_giangday_monhoc` FOREIGN KEY (`mamonhoc`) REFERENCES `monhoc` (`mamonhoc`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_phancong_nguoidung` FOREIGN KEY (`manguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Các ràng buộc cho bảng `trangthaithongbao`
--
ALTER TABLE `trangthaithongbao`
  ADD CONSTRAINT `trangthaithongbao_ibfk_1` FOREIGN KEY (`matb`) REFERENCES `thongbao` (`matb`) ON DELETE CASCADE,
  ADD CONSTRAINT `trangthaithongbao_ibfk_2` FOREIGN KEY (`manguoidung`) REFERENCES `nguoidung` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
