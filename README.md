# School Classroom Feedback Survey System

> **Simple student feedback collection with Chart.js dashboard analytics**

<br>

**Check out our website**

Wasmer (Main): https://school-survey.wasmer.app

InfinityFree (Mirror): https://classroomsurvey4.42web.io

## Overview

A lightweight web platform for schools to collect and visualize student feedback across surveys. It Collects student feedback across 5 categories (Teaching Skills, Classroom Management, Engagement, Assignments, Professionalism) and displays analytics in an admin dashboard with 6 chart types.

### Backend Logic

| Feature                        | Description                                       |
| ------------------------------ | ------------------------------------------------- |
| User Input Collection          | Student information and survey responses          |
| Data Validation & Sanitization | Prevent errors and attacks                        |
| Session Management             | Persist data across steps                         |
| Security Measures              | CSRF, XSS, and SQL injection protection           |
| Database Interaction           | Store/retrieve survey data for data visualization |
| Server Side Rendering          | Fetch database data to generate HTML              |

## Tech Stack

- **Backend**: PHP 8.3.6
- **Database**: MySQL
- **Frontend**: HTML, CSS, JavaScript
- **Charts**: Chart.js
- **Icons**: Lucide
- **Deployment**: Wasmer, InfinityFree (Alternative)

## Screenshots

<table width="100%">
	<tr>
		<th>Homepage</th>
		<th>Student Information</th>
	</tr>
	<tr>
		<td width="50%">
			<img src="https://github.com/friaearl-bit/school-survey/blob/main/images/homepage.png" />
		</td>
		<td width="50%">
			<img src="https://github.com/friaearl-bit/school-survey/blob/main/images/student-info.png" />
		</td>
	</tr>
	<tr>
		<th>Classroom Survey</th>
		<th>Thank You</th>
	</tr>
	<tr>
		<td width="50%">
			<img src="https://github.com/friaearl-bit/school-survey/blob/main/images/survey.png" />
		</td>
		<td width="50%">
			<img src="https://github.com/friaearl-bit/school-survey/blob/main/images/thank-you.png" />
		</td>
	</tr>
	<tr>
		<th>Admin Panel</th>
		<th>Super Admin</th>
	</tr>
		<tr>
		<td width="50%">
			<img src="https://github.com/friaearl-bit/school-survey/blob/main/images/admin.png" />
		</td>
		<td width="50%">
			<img src="https://github.com/friaearl-bit/school-survey/blob/main/images/superadmin.png" />
		</td>
	</tr>
</table>

## Database Schema (ERDiagram)

![Diagram](https://github.com/friaearl-bit/school-survey/blob/main/images/diagram.svg)

## Dashboard Analytics

### Summary Cards

- Total Responses
- Average Rating
- Total Survey Targets
- Total Students

### Charts

| Chart                | Title                              |
| -------------------- | ---------------------------------- |
| Radar Chart          | Average Score by Category          |
| Doughnut Chart       | Responses by Section               |
| Line Chart           | Responses Over Time                |
| Pie Chart            | Anonymous vs. Named Responses      |
| Horizontal Bar Chart | Top 5 Highest-Rated Questions      |
| Bar Chart            | Score Distribution                 |
| Grouped Bar Chart    | Instructor Performance by Category |
