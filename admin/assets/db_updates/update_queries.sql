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
  `tax_percentage` float NOT NULL,
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