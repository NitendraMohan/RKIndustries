--
-- Database: `rkindustries`
--

-- --------------------------------------------------------
-- date: 25-May-2024 11:36 AM
--
-- Table structure for table `company_master`
--

CREATE TABLE `company_master` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `gst_no` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `contact_number` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `createdat` timestamp NOT NULL D0EFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company_master`
--

INSERT INTO `company_master` (`id`, `company_name`, `gst_no`, `address`, `contact_number`, `email`, `logo`, `createdat`, `updatedat`) VALUES
(1, 'R K INDUSTRIES', '565FTRTRTR55', 'GURGAON', '6788934567', 'ABC@GMAIL.COM', '', '2024-05-24 16:42:43', '2024-05-24 16:42:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_master`
--
ALTER TABLE `company_master`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_master`
--
ALTER TABLE `company_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;
-- --------------------------------------------------------
-- date: 26-May-2024 -4:00 PM
--
-- Table structure for table `user_actions_log`
--

CREATE TABLE `user_actions_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `record_id` int(11) NOT NULL,
  `action_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `details` text DEFAULT NULL,
  `previous_data` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for table `user_actions_log`
--
ALTER TABLE `user_actions_log`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_actions_log`
--
ALTER TABLE `user_actions_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
COMMIT;

-- Date: 27-May-2024 03:00 PM
-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `role` varchar(6) NOT NULL,
  `username` varchar(255) NOT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

-- Date: 31-May-2024 12:10 PM
-- --------------------------------------------------------

--
-- Table structure for table `tbl_modules`
--

CREATE TABLE `tbl_modules` (
  `id` int(11) NOT NULL,
  `module_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_modules`
--

INSERT INTO `tbl_modules` (`id`, `module_name`, `file_path`) VALUES
(1, 'Session Master', '../view/sessionView.php'),
(2, 'Company Master', '../view/companyView.php'),
(3, 'Department Master', '../view/departmentView.php'),
(4, 'User Master', '../view/usersView.php'),
(5, 'User Permissions', '../view/userPermissionsView.php'),
(6, 'Unit Master', '../view/unitView.php'),
(7, 'Vendor Master', '../view/vendorView.php'),
(8, 'Party Master', '../view/partyView.php'),
(9, 'Tax Master', '../view/taxView.php'),
(10, 'Category Master', '../view/categoryView.php'),
(11, 'Branch Master', '../view/branchView.php'),
(12, 'BOM Master', '../view/BOMView.php'),
(13, 'PO Generation', '../view/POGeneration.php'),
(14, 'Purchase', '../view/purchaseView.php'),
(15, 'Purchase Return', '../view/purchaseReturnView.php'),
(16, 'Transfer', '../view/transferView.php'),
(17, 'Sale', '../view/saleView.php'),
(18, 'Sale Return', '../view/saleReturnView.php'),
(19, 'User Log', '../view/usersLogView.php');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_modules`
--
ALTER TABLE `tbl_modules`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_modules`
--
ALTER TABLE `tbl_modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;
-- --------------------------------------------------------

--
-- Table structure for table `tbl_user_permissions`
--

CREATE TABLE `tbl_user_permissions` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `moduleid` int(11) NOT NULL,
  `insert_record` tinyint(4) NOT NULL,
  `update_record` tinyint(4) NOT NULL,
  `delete_record` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_user_permissions`
--
ALTER TABLE `tbl_user_permissions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_user_permissions`
--
ALTER TABLE `tbl_user_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

--Date: 03-June-2024
-- --------------------------------------------------------

--
-- Table structure for table `tbl_taxes`
--

CREATE TABLE `tbl_taxes` (
  `id` int(11) NOT NULL,
  `tax_name` varchar(255) NOT NULL,
  `tax_percentage` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Indexes for table `tbl_taxes`
--
ALTER TABLE `tbl_taxes`
  ADD PRIMARY KEY (`id`);
-- AUTO_INCREMENT for table `tbl_taxes`
--
ALTER TABLE `tbl_taxes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_vendors`
--

CREATE TABLE `tbl_vendors` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `comp_name` varchar(255) NOT NULL,
  `gstno` varchar(255) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Indexes for table `tbl_vendors`
--
ALTER TABLE `tbl_vendors`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for table `tbl_vendors`
--
ALTER TABLE `tbl_vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
  -- --------------------------------------------------------

--
-- Table structure for table `tbl_parties`
--

CREATE TABLE `tbl_parties` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `party_name` varchar(255) NOT NULL,
  `comp_name` varchar(255) NOT NULL,
  `gstno` varchar(255) NOT NULL,
  `mobile` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Indexes for table `tbl_vendors`
--
ALTER TABLE `tbl_parties`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for table `tbl_vendors`
--
ALTER TABLE `tbl_parties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `compid`, `category_name`, `status`, `createdat`, `updatedat`) VALUES
(1, 1, 'RAW MATERIAL', 1, '2024-06-03 10:32:19', '2024-06-03 10:32:19'),
(2, 1, 'FINAL PRODUCT', 1, '2024-06-03 10:32:38', '2024-06-03 10:32:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
-- --------------------------------------------------------

-- INSERT SUB CATEGORY MODULE IN tbl_modules

INSERT INTO `tbl_modules` (`module_name`, `file_path`) VALUES ('Product Sub Category', '../view/subcategoryView.php')
-- --------------------------------------------------------
--
-- Table structure for table `tbl_subcategory`
--

CREATE TABLE `tbl_subcategory` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subcategory`
--

INSERT INTO `tbl_subcategory` (`id`, `compid`, `category_id`, `subcategory_name`, `status`, `createdat`, `updatedat`) VALUES
(5, 1, 2, 'BAG', 1, '2024-06-03 11:55:42', '2024-06-03 11:55:42'),
(6, 1, 2, 'HELMET', 1, '2024-06-03 11:56:02', '2024-06-03 11:56:02'),
(7, 1, 1, 'FOAM', 1, '2024-06-03 12:03:50', '2024-06-03 12:03:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_subcategory`
--
ALTER TABLE `tbl_subcategory`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_subcategory`
--
ALTER TABLE `tbl_subcategory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Table structure for table `tbl_branch`
--
  CREATE TABLE `tbl_branch` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `address` varchar(500) NOT NULL,
  `pincode` varchar(6) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_branch`
--

INSERT INTO `tbl_branch` (`id`, `compid`, `branch_name`, `address`, `pincode`, `status`, `createdat`, `updatedat`) VALUES
(7, 1, 'BRANCH1', 'R101B Gulab nagar bankhana Bareilly ', '243001', 1, '2024-06-03 11:47:51', '2024-06-03 11:47:51'),
(9, 1, 'BRANCH12', 'sdfdsf', '444444', 1, '2024-06-03 11:50:38', '2024-06-03 11:50:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_branch`
--
ALTER TABLE `tbl_branch`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_branch`
--
ALTER TABLE `tbl_branch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE `tbl_products` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_products`
--

INSERT INTO `tbl_products` (`id`, `compid`, `category_id`, `subcategory_id`, `product_name`, `unit_id`, `price`, `image`, `status`, `createdat`, `updatedat`) VALUES
(1, 1, 2, 6, '3MM_FOAM', 8, 30, '../images/favicon.png', 1, '2024-06-04 07:05:52', '2024-06-04 07:05:52');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_products`
--
ALTER TABLE `tbl_products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_products`
--
ALTER TABLE `tbl_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Table structure for table `tbl_deparment`
--

CREATE TABLE `tbl_deparment` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `branchid` int(11) NOT NULL,
  `dept_name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_deparment`
--

INSERT INTO `tbl_deparment` (`id`, `compid`, `branchid`, `dept_name`, `status`, `createdat`, `updatedat`) VALUES
(3, 1, 7, 'CUTTINGWQEQ', 1, '2024-06-04 10:40:12', '2024-06-04 10:40:12'),
(7, 1, 7, 'NEW DEPT', 1, '2024-06-04 13:05:32', '2024-06-04 13:05:32'),
(8, 1, 7, 'NEW DEPT2', 1, '2024-06-04 13:09:09', '2024-06-04 13:09:09'),
(9, 1, 9, 'CUTTING', 1, '2024-06-04 13:17:31', '2024-06-04 13:17:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_deparment`
--
ALTER TABLE `tbl_deparment`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_deparment`
--
ALTER TABLE `tbl_deparment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

--
-- Table structure for table `tbl_brand`
--

CREATE TABLE `tbl_brand` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brand`
--

INSERT INTO `tbl_brand` (`id`, `compid`, `brand_name`, `status`, `createdat`, `updatedat`) VALUES
(2, 1, 'MRFSADASDSADSA', 0, '2024-06-05 08:17:32', '2024-06-05 08:17:32'),
(3, 1, 'NIKR', 0, '2024-06-05 08:24:33', '2024-06-05 08:24:33'),
(5, 1, 'STYLE', 1, '2024-06-05 08:41:53', '2024-06-05 08:41:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_brand`
--
ALTER TABLE `tbl_brand`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;


--
-- Table structure for table `tbl_brandproduct`
--
CREATE TABLE `tbl_brandproduct` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `brandid` int(11) NOT NULL,
  `productid` int(11) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_brandproduct`
--

INSERT INTO `tbl_brandproduct` (`id`, `compid`, `brandid`, `productid`, `status`, `createdat`, `updatedat`) VALUES
(10, 1, 5, 1, 1, '2024-06-05 11:53:05', '2024-06-05 11:53:05'),
(14, 1, 8, 1, 1, '2024-06-06 06:40:47', '2024-06-06 06:40:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_brandproduct`
--
ALTER TABLE `tbl_brandproduct`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_brandproduct`
--
ALTER TABLE `tbl_brandproduct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_bom_product`
--

CREATE TABLE `tbl_bom_product` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `bom_name` varchar(255) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `qty` int(11) NOT NULL,
  `detail` varchar(500) NOT NULL,
  `image` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_bom_product`
--

INSERT INTO `tbl_bom_product` (`id`, `compid`, `bom_name`, `category_id`, `subcategory_id`, `product_id`, `brand_id`, `unit_id`, `qty`, `detail`, `image`, `status`, `createdat`, `updatedat`) VALUES
(1, 1, 'ABC BOM', 2, 5, 1, 5, 8, 1, 'BACK AND RED', '../images/student-icon-image-for-students.png', 1, '2024-06-06 12:32:51', '2024-06-06 12:32:51'),
(2, 1, 'ABC', 2, 6, 3, 5, 17, 1, 'yellow and green', '../images/favicon.png', 1, '2024-06-06 16:23:02', '2024-06-06 16:23:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bom_product`
--
ALTER TABLE `tbl_bom_product`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bom_product`
--
ALTER TABLE `tbl_bom_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_BOM_material`
--

CREATE TABLE `tbl_BOM_material` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `bom_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `subcategory_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `qty` int(11) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_BOM_material`
--
ALTER TABLE `tbl_BOM_material`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_BOM_material`
--
ALTER TABLE `tbl_BOM_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

--
-- Table structure for table `tbl_designation`
--
use rkindustry;
CREATE TABLE `tbl_designation` (
  `id` int(11) NOT NULL,
  `designation_name` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_designation`
--

INSERT INTO `tbl_designation` (`id`, `designation_name`, `status`, `createdat`, `updatedat`) VALUES
(19, 'DIRECTOR', 1, '2024-06-13 09:22:06', '2024-06-13 09:22:06'),
(20, 'MANAGER', 1, '2024-06-13 09:22:15', '2024-06-13 09:22:15'),
(21, 'SUPERVISOR', 1, '2024-06-13 09:22:23', '2024-06-13 09:22:23'),
(22, 'TEAM LEADER', 1, '2024-06-13 09:22:34', '2024-06-13 09:22:34'),
(23, 'WORKER', 1, '2024-06-13 09:23:25', '2024-06-13 09:23:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_designation`
--
ALTER TABLE `tbl_designation`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_designation`
--
ALTER TABLE `tbl_designation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_other_charges`
--

CREATE TABLE `tbl_other_charges` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `expanse_name` varchar(255) NOT NULL,
  `detail` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_other_charges`
--

INSERT INTO `tbl_other_charges` (`id`, `compid`, `expanse_name`, `detail`, `status`, `createdat`, `updatedat`) VALUES
(1, 1, 'TRANSPORTATION %', 'applicable only on selected products', 1, '2024-06-15 06:29:55', '2024-06-15 06:29:55'),
(2, 1, 'LABOUR COST', 'applicable on materials', 1, '2024-06-15 07:11:12', '2024-06-15 07:11:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_other_charges`
--
ALTER TABLE `tbl_other_charges`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_other_charges`
--
ALTER TABLE `tbl_other_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------

--
-- Table structure for table `bom_other_charges`
--

CREATE TABLE `bom_other_charges` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `bom_id` int(11) NOT NULL,
  `charge_id` int(11) NOT NULL,
  `is_percentage` tinyint(4) NOT NULL DEFAULT 0,
  `apply_on_material` tinyint(4) NOT NULL DEFAULT 0,
  `charge_value` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bom_other_charges`
--


INSERT INTO `bom_other_charges` (`id`, `compid`, `bom_id`, `charge_id`, `is_percentage`, `apply_on_material`, `charge_value`, `status`, `createdat`, `updatedat`) VALUES
(3, 1, 5, 2, 0, 0, 50.00, 1, '2024-06-17 09:16:00', '2024-06-17 09:16:00'),
(4, 1, 5, 1, 0, 0, 60.00, 1, '2024-06-17 10:13:26', '2024-06-17 10:13:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bom_other_charges`
--
ALTER TABLE `bom_other_charges`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bom_other_charges`
--
ALTER TABLE `bom_other_charges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_stock`
--

CREATE TABLE `tbl_stock` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `prod_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_stock`
--

INSERT INTO `tbl_stock` (`id`, `compid`, `prod_id`, `dept_id`, `unit_id`, `rate`, `qty`, `status`, `createdat`, `updatedat`) VALUES
(1, 1, 1, 14, 91, 31.00, 10.00, 1, '2024-06-19 07:08:05', '2024-06-19 07:08:05'),
(2, 1, 13, 14, 91, 41.60, 15.00, 1, '2024-06-19 07:09:01', '2024-06-19 07:09:01'),
(3, 1, 17, 14, 91, 4.25, 80.00, 1, '2024-06-19 07:09:39', '2024-06-19 07:09:39'),
(4, 1, 10, 14, 91, 128.00, 80.00, 1, '2024-06-19 07:10:34', '2024-06-19 07:10:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_stock`
--
ALTER TABLE `tbl_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sale_order`
--

CREATE TABLE `tbl_sale_order` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `party_id` int(11) NOT NULL,
  `bill_no` varchar(10) NOT NULL,
  `order_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `voucher_no` int(11) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `delivery_address` varchar(500) NOT NULL,
  `terms` varchar(500) NOT NULL,
  `other_detail` varchar(500) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_sale_order`
--

INSERT INTO `tbl_sale_order` (`id`, `compid`, `party_id`, `bill_no`, `order_date`, `delivery_date`, `voucher_no`, `payment_mode`, `delivery_address`, `terms`, `other_detail`, `status`, `createdat`, `updatedat`) VALUES
(1, 1, 1, '1', '2024-06-01', '2024-06-30', 2, 'Cheque', '', '', '', 1, '2024-06-20 13:14:53', '2024-06-20 13:14:53'),
(2, 1, 2, '3', '2024-06-16', '2024-06-28', 2, 'Online', 'gurgaon', 'sale will not be returned', 'other expanses will be charged sperately', 1, '2024-06-20 13:24:08', '2024-06-20 13:24:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_sale_order`
--
ALTER TABLE `tbl_sale_order`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_sale_order`
--
ALTER TABLE `tbl_sale_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sale_order_products`
--

CREATE TABLE `tbl_sale_order_products` (
  `id` int(11) NOT NULL,
  `compid` int(11) NOT NULL,
  `saleorder_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_amt` decimal(10,2) NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `createdat` timestamp NOT NULL DEFAULT current_timestamp(),
  `updatedat` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_sale_order_products`
--

INSERT INTO `tbl_sale_order_products` (`id`, `compid`, `saleorder_id`, `brand_id`, `product_id`, `rate`, `unit_id`, `qty`, `tax_id`, `tax_amt`, `cost`, `total_cost`, `status`, `createdat`, `updatedat`) VALUES
(3, 1, 1, 9, 9, 3000.00, 75, 6.00, 3, 396.00, 18000.00, 18396.00, 1, '2024-06-21 09:02:12', '2024-06-21 09:02:12'),
(4, 1, 1, 8, 2, 30.00, 75, 2004.00, 1, 2525.04, 60120.00, 62645.04, 1, '2024-06-21 09:06:45', '2024-06-21 09:06:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_sale_order_products`
--
ALTER TABLE `tbl_sale_order_products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_sale_order_products`
--
ALTER TABLE `tbl_sale_order_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;