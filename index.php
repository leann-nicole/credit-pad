<?php
session_start(); ?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Credit Pad</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
    <link rel="stylesheet" href="style.css"/>
  </head>
  <body>
    <p id="error" class="<?php if (!isset($_GET['error'])) {
        echo 'hidden-item';
    } ?>">
        <?php if (isset($_GET['error'])) {
            echo $_GET['error'];
        } ?>
    </p>      
    <header id="top">
      <p id="sitename-header"><a href="
      <?php if (isset($_SESSION['ownerLoggedIn'])) {
          echo 'customers.php';
      } elseif (isset($_SESSION['customerLoggedIn'])) {
          echo 'customer-home.php';
      } elseif (isset($_SESSION['adminLoggedIn'])) {
          echo 'applicants.php';
      } else {
          echo 'login.php';
      } ?>">Credit Pad</a></p>
      <?php if (
          !isset($_SESSION['ownerLoggedIn']) &&
          !isset($_SESSION['customerLoggedIn']) &&
          !isset($_SESSION['adminLoggedIn'])
      ) { ?>
      <div id="header-login-signup-buttons-div">
        <a href="login.php" class="button" id="login-button">Log in</a>
        <a href="signup.php" class="button" id="signup-button">Sign up</a>
      </div>
      <?php } else { ?>
      <div id="dropdown">
        <button type="button" id="dropdown-button" class="material-icons" onclick="toggleAccountOptions()"><?php if (
            isset($_SESSION['ownerLoggedIn'])
        ) {
            echo 'storefront';
        } else {
            echo 'person';
        } ?><span class="material-icons">arrow_drop_down</span></button>
        <div id="dropdown-menu" class="hidden-item">
          <a href="#">Profile</a>
          <a href="logout.php">Log out</a>
        </div>
      </div>
      <?php } ?>
    </header>
    <div id="guide-content">
      <nav>
        <ul>
          <li class="navItem"><a href="#about-section" onclick="makeActive(1)">ABOUT</a></li>
          <li class="navItem"><a href="#terms-of-use-section" onclick="makeActive(2)">TERMS OF USE</a></li>
          <li class="navItem"><a href="#privacy-policy-section" onclick="makeActive(3)">PRIVACY POLICY</a></li>
          <li class="navItem"><a href="#contact-us-section" onclick="makeActive(4)">CONTACT US</a></li>
        </ul>
      </nav>
      <main>
          <div class="container" id="guide-container">
              <div id="splash-div">
                <div id="splash-greeting-div">
                  <p>Welcome to</p>
                  <p>Credit Pad</p>
                  <p>Our goal is to improve the way sari&#8209;sari stores manage credit transactions with their customers with the help of different web technologies.</p>
                  <p>#BetterRecordkeeping</p>
                </div>
                <img src="images/splash_image.jpg" alt="representation_of_online_store">
              </div>
              <span class="offsetElement"></span>
              <section class="guide-section" data-id="#about-section">
                <p>About</p><br>
                <p>Credit Pad is a web application designed to function as a customer credit recordkeeping system for sari-sari stores. The goal is to improve the way sari-sari stores manage credit transactions with their customers by using web technologies. Compared to the traditional pen and paper method, Credit Pad is a digital solution that encourages better recordkeeping practices. It provides sari-sari store owners a more convenient, more secure, and more environmental-friendly system for recording customer credit transactions.</p>
                <br>
                <p>The system has three types of users, namely, store owners, customers, and administrators. The following is a list of features and functionalities associated with each.</p>
                <br>
                <h3><strong>Store Owners</strong></h3>
                <br>
                <ul>
                  <li>customer list</li><br>
                  <li>product inventory</li><br>
                  <li>transaction history</li><br>
                  <li>financial report</li><br>
                  <li>notes</li><br>
                </ul>
                <p>By signing up, a store owner submits an application to create an account for their store. Applicants are notified whether their application is accepted or rejected via email. After the administrator approves the application, the store owner can create and delete customer accounts under the name of their store.</p>
                <br>
                <h3><strong>Store Customers</strong></h3>
                <br>
                <ul>
                  <li>transaction history</li><br>
                  <li>financial report</li><br>
                  <li>notes</li><br>
                </ul>
                <p>Store customers can sign up by providing the name of their account, as created by the store owner, and the name of the store. By signing up, customers can view their history of transactions with a particular store.</p>
                <br>
                <h3><strong>Administrators</strong></h3>
                <br>
                <ul>
                  <li>applicant list</li><br>
                  <li>store list</li><br>
                  <li>notes</li><br>
                </ul>
                <p>Administrators can log in using the administrator account. The administrator is responsible for accepting or rejecting applications submitted by store owners.</p>
              </section>
              <span id="terms-of-use-section" class="offsetElement"></span>
              <section class="guide-section" data-id="#terms-of-use-section">
                <p>Terms of Use</p><br>
                <p>Welcome to Credit Pad!</p>
                <br>
                <p>These terms and conditions outline the rules and regulations for the use of Credit Pad's Website, located at www.creditpad.com.</p>
                <br>
                <p>By accessing this website we assume you accept these terms and conditions. Do not continue to use Credit Pad if you do not agree to take all of the terms and conditions stated on this page.</p>
                <br>
                <p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and all Agreements: "Client", "You" and "Your" refers to you, the person log on this website and compliant to the Company’s terms and conditions. "The Company", "Ourselves", "We", "Our" and "Us", refers to our Company. "Party", "Parties", or "Us", refers to both the Client and ourselves. All terms refer to the offer, acceptance and consideration of payment necessary to undertake the process of our assistance to the Client in the most appropriate manner for the express purpose of meeting the Client’s needs in respect of provision of the Company’s stated services, in accordance with and subject to, prevailing law of Netherlands. Any use of the above terminology or other words in the singular, plural, capitalization and/or he/she or they, are taken as interchangeable and therefore as referring to same.</p>
                <br>
                <h3><strong>Cookies</strong></h3>
                <br>
                <p>We employ the use of cookies. By accessing Credit Pad, you agreed to use cookies in agreement with the Credit Pad's Privacy Policy. </p>
                <br>
                <p>Most interactive websites use cookies to let us retrieve the user’s details for each visit. Cookies are used by our website to enable the functionality of certain areas to make it easier for people visiting our website. Some of our affiliate/advertising partners may also use cookies.</p>
                <br>
                <h3><strong>License</strong></h3>
                <br>
                <p>Unless otherwise stated, Credit Pad and/or its licensors own the intellectual property rights for all material on Credit Pad. All intellectual property rights are reserved. You may access this from Credit Pad for your own personal use subjected to restrictions set in these terms and conditions.</p>
                <br>
                <p>You must not:</p>
                <br>
                <ul>
                  <li>Republish material from Credit Pad</li><br>
                  <li>Sell, rent or sub-license material from Credit Pad</li><br>
                  <li>Reproduce, duplicate or copy material from Credit Pad</li><br>
                  <li>Redistribute content from Credit Pad</li><br>
                </ul>
                <p>This Agreement shall begin on the date hereof. Our Terms and Conditions were created with the help of the <a href="https://www.termsfeed.com/terms-conditions-generator/">TermsFeed Free Terms and Conditions Generator</a>.</p>
                <br>
                <p>Parts of this website offer an opportunity for users to post and exchange opinions and information in certain areas of the website. Credit Pad does not filter, edit, publish or review Comments prior to their presence on the website. Comments do not reflect the views and opinions of Credit Pad,its agents and/or affiliates. Comments reflect the views and opinions of the person who post their views and opinions. To the extent permitted by applicable laws, Credit Pad shall not be liable for the Comments or for any liability, damages or expenses caused and/or suffered as a result of any use of and/or posting of and/or appearance of the Comments on this website.</p>
                <br>
                <p>Credit Pad reserves the right to monitor all Comments and to remove any Comments which can be considered inappropriate, offensive or causes breach of these Terms and Conditions.</p>
                <br>
                <p>You warrant and represent that:</p>
                <br>
                <ul>
                  <li>You are entitled to post the Comments on our website and have all necessary licenses and consents to do so;</li><br>
                  <li>The Comments do not invade any intellectual property right, including without limitation copyright, patent or trademark of any third party;</li><br>
                  <li>The Comments do not contain any defamatory, libelous, offensive, indecent or otherwise unlawful material which is an invasion of privacy</li><br>
                  <li>The Comments will not be used to solicit or promote business or custom or present commercial activities or unlawful activity.</li><br>
                </ul>
                <p>You hereby grant Credit Pad a non-exclusive license to use, reproduce, edit and authorize others to use, reproduce and edit any of your Comments in any and all forms, formats or media.</p>
                <br>
                <h3><strong>Hyperlinking to our Content</strong></h3>
                <br>
                <p>The following organizations may link to our Website without prior written approval:</p>
                <br>
                <ul>
                    <li>Government agencies;</li><br>
                    <li>Search engines;</li><br>
                    <li>News organizations;</li><br>
                    <li>Online directory distributors may link to our Website in the same manner as they hyperlink to the Websites of other listed businesses; and</li><br>
                    <li>System wide Accredited Businesses except soliciting non-profit organizations, charity shopping malls, and charity fundraising groups which may not hyperlink to our Web site.</li><br>
                </ul>
                <p>These organizations may link to our home page, to publications or to other Website information so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products and/or services; and (c) fits within the context of the linking party’s site.</p>
                <br>
                <p>We may consider and approve other link requests from the following types of organizations:</p>
                <br>
                <ul>
                  <li>commonly-known consumer and/or business information sources;</li><br>
                  <li>dot.com community sites;</li><br>
                  <li>associations or other groups representing charities;</li><br>
                  <li>online directory distributors;</li><br>
                  <li>internet portals;</li><br>
                  <li>accounting, law and consulting firms; and</li><br>
                  <li>educational institutions and trade associations.</li><br>
                </ul>
                <p>We will approve link requests from these organizations if we decide that: (a) the link would not make us look unfavorably to ourselves or to our accredited businesses; (b) the organization does not have any negative records with us; (c) the benefit to us from the visibility of the hyperlink compensates the absence of Credit Pad; and (d) the link is in the context of general resource information.</p>
                <br>
                <p>These organizations may link to our home page so long as the link: (a) is not in any way deceptive; (b) does not falsely imply sponsorship, endorsement or approval of the linking party and its products or services; and (c) fits within the context of the linking party’s site.</p>
                <br>
                <p>If you are one of the organizations listed in paragraph 2 above and are interested in linking to our website, you must inform us by sending an e-mail to Credit Pad. Please include your name, your organization name, contact information as well as the URL of your site, a list of any URLs from which you intend to link to our Website, and a list of the URLs on our site to which you would like to link. Wait 2-3 weeks for a response.</p>
                <br>
                <p>Approved organizations may hyperlink to our Website as follows:</p>
                <br>
                <ul>
                  <li>By use of our corporate name; or</li><br>
                  <li>By use of the uniform resource locator being linked to; or</li><br>
                  <li>By use of any other description of our Website being linked to that makes sense within the context and format of content on the linking party’s site.</li><br>
                </ul>
                <p>No use of Credit Pad's logo or other artwork will be allowed for linking absent a trademark license agreement.</p>
                <br>
                <h3><strong>iFrames</strong></h3>
                <br>
                <p>Without prior approval and written permission, you may not create frames around our Webpages that alter in any way the visual presentation or appearance of our Website.</p>
                <br>
                <h3><strong>Content Liability</strong></h3>
                <br>
                <p>We shall not be hold responsible for any content that appears on your Website. You agree to protect and defend us against all claims that is rising on your Website. No link(s) should appear on any Website that may be interpreted as libelous, obscene or criminal, or which infringes, otherwise violates, or advocates the infringement or other violation of, any third party rights.</p>
                <br>
                <h3><strong>Your Privacy</strong></h3>
                <br>
                <p>Please read Privacy Policy</p>
                <br>
                <h3><strong>Reservation of Rights</strong></h3>
                <br>
                <p>We reserve the right to request that you remove all links or any particular link to our Website. You approve to immediately remove all links to our Website upon request. We also reserve the right to amen these terms and conditions and it’s linking policy at any time. By continuously linking to our Website, you agree to be bound to and follow these linking terms and conditions.</p>
                <br>
                <h3><strong>Removal of links from our website</strong></h3>
                <br>
                <p>If you find any link on our Website that is offensive for any reason, you are free to contact and inform us any moment. We will consider requests to remove links but we are not obligated to or so or to respond to you directly.</p>
                <br>
                <p>We do not ensure that the information on this website is correct, we do not warrant its completeness or accuracy; nor do we promise to ensure that the website remains available or that the material on the website is kept up to date.</p>
                <br>
                <h3><strong>Disclaimer</strong></h3>
                <br>
                <p>To the maximum extent permitted by applicable law, we exclude all representations, warranties and conditions relating to our website and the use of this website. Nothing in this disclaimer will:</p>
                <br>
                <ul>
                  <li>limit or exclude our or your liability for death or personal injury;</li><br>
                  <li>limit or exclude our or your liability for fraud or fraudulent misrepresentation;</li><br>
                  <li>limit any of our or your liabilities in any way that is not permitted under applicable law; or</li><br>
                  <li>exclude any of our or your liabilities that may not be excluded under applicable law.</li><br>
                </ul>
                <p>The limitations and prohibitions of liability set in this Section and elsewhere in this disclaimer: (a) are subject to the preceding paragraph; and (b) govern all liabilities arising under the disclaimer, including liabilities arising in contract, in tort and for breach of statutory duty.</p>
                <br>
                <p>As long as the website and the information and services on the website are provided free of charge, we will not be liable for any loss or damage of any nature.</p>
              </section>
              <span id="privacy-policy-section" class="offsetElement"></span>
              <section class="guide-section" data-id="#privacy-policy-section">
                <p>Privacy Policy</p><br>
                <p>Last updated: June 09, 2022</p><br>
                <p>This Privacy Policy describes Our policies and procedures on the collection, use and disclosure of Your information when You use the Service and tells You about Your privacy rights and how the law protects You.</p>
                <br>
                <p>We use Your Personal data to provide and improve the Service. By using the Service, You agree to the collection and use of information in accordance with this Privacy Policy. This Privacy Policy has been created with the help of the <a href="https://www.termsfeed.com/privacy-policy-generator/" target="_blank">TermsFeed Privacy Policy Generator</a>.</p>
                <br>
                <h1>Interpretation and Definitions</h1>
                <br>
                <h2>Interpretation</h2>
                <br>
                <p>The words of which the initial letter is capitalized have meanings defined under the following conditions. The following definitions shall have the same meaning regardless of whether they appear in singular or in plural.</p>
                <br>
                <h2>Definitions</h2>
                <br>
                <p>For the purposes of this Privacy Policy:</p>
                <br>
                <ul>
                  <li>
                  <p><strong>Account</strong> means a unique account created for You to access our Service or parts of our Service.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>Affiliate</strong> means an entity that controls, is controlled by or is under common control with a party, where &quot;control&quot; means ownership of 50% or more of the shares, equity interest or other securities entitled to vote for election of directors or other managing authority.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>Application</strong> means the software program provided by the Company downloaded by You on any electronic device, named Credit Pad</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>Company</strong> (referred to as either &quot;the Company&quot;, &quot;We&quot;, &quot;Us&quot; or &quot;Our&quot; in this Agreement) refers to Credit Pad.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>Country</strong> refers to:  Philippines</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>Device</strong> means any device that can access the Service such as a computer, a cellphone or a digital tablet.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>Personal Data</strong> is any information that relates to an identified or identifiable individual.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>Service</strong> refers to the Application.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>Service Provider</strong> means any natural or legal person who processes the data on behalf of the Company. It refers to third-party companies or individuals employed by the Company to facilitate the Service, to provide the Service on behalf of the Company, to perform services related to the Service or to assist the Company in analyzing how the Service is used.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>Usage Data</strong> refers to data collected automatically, either generated by the use of the Service or from the Service infrastructure itself (for example, the duration of a page visit).</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>You</strong> means the individual accessing or using the Service, or the company, or other legal entity on behalf of which such individual is accessing or using the Service, as applicable.</p>
                  </li>
                  <br>
                </ul>
                <br>
                <h1>Collecting and Using Your Personal Data</h1>
                <br>
                <h2>Types of Data Collected</h2>
                <br>
                <h3>Personal Data</h3>
                <br>
                <p>While using Our Service, We may ask You to provide Us with certain personally identifiable information that can be used to contact or identify You. Personally identifiable information may include, but is not limited to:</p>
                <br>
                <ul>
                  <li>
                  <p>Email address</p>
                  </li>
                  <br>
                  <li>
                  <p>First name and last name</p>
                  </li>
                  <br>
                  <li>
                  <p>Phone number</p>
                  </li>
                  <br>
                  <li>
                  <p>Address, State, Province, ZIP/Postal code, City</p>
                  </li>
                  <br>
                  <li>
                  <p>Usage Data</p>
                  </li>
                  <br>
                </ul>
                <br>
                <h3>Usage Data</h3>
                <br>
                <p>Usage Data is collected automatically when using the Service.</p>
                <br>
                <p>Usage Data may include information such as Your Device's Internet Protocol address (e.g. IP address), browser type, browser version, the pages of our Service that You visit, the time and date of Your visit, the time spent on those pages, unique device identifiers and other diagnostic data.</p>
                <br>
                <p>When You access the Service by or through a mobile device, We may collect certain information automatically, including, but not limited to, the type of mobile device You use, Your mobile device unique ID, the IP address of Your mobile device, Your mobile operating system, the type of mobile Internet browser You use, unique device identifiers and other diagnostic data.</p>
                <br>
                <p>We may also collect information that Your browser sends whenever You visit our Service or when You access the Service by or through a mobile device.</p>
                <br>
                <br>
                <h3>Information Collected while Using the Application</h3>
                <br>
                <p>While using Our Application, in order to provide features of Our Application, We may collect, with Your prior permission:</p>
                <br>
                <ul>
                  <li>Pictures and other information from your Device's camera and photo library</li>
                  <br>
                </ul>
                <p>We use this information to provide features of Our Service, to improve and customize Our Service. The information may be uploaded to the Company's servers and/or a Service Provider's server or it may be simply stored on Your device.</p>
                <br>
                <p>You can enable or disable access to this information at any time, through Your Device settings.</p>
                <br>
                <h2>Use of Your Personal Data</h2>
                <br>
                <p>The Company may use Personal Data for the following purposes:</p>
                <br>
                <ul>
                  <li>
                  <p><strong>To provide and maintain our Service</strong>, including to monitor the usage of our Service.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>To manage Your Account:</strong> to manage Your registration as a user of the Service. The Personal Data You provide can give You access to different functionalities of the Service that are available to You as a registered user.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>For the performance of a contract:</strong> the development, compliance and undertaking of the purchase contract for the products, items or services You have purchased or of any other contract with Us through the Service.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>To contact You:</strong> To contact You by email, telephone calls, SMS, or other equivalent forms of electronic communication, such as a mobile application's push notifications regarding updates or informative communications related to the functionalities, products or contracted services, including the security updates, when necessary or reasonable for their implementation.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>To provide You</strong> with news, special offers and general information about other goods, services and events which we offer that are similar to those that you have already purchased or enquired about unless You have opted not to receive such information.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>To manage Your requests:</strong> To attend and manage Your requests to Us.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>For business transfers:</strong> We may use Your information to evaluate or conduct a merger, divestiture, restructuring, reorganization, dissolution, or other sale or transfer of some or all of Our assets, whether as a going concern or as part of bankruptcy, liquidation, or similar proceeding, in which Personal Data held by Us about our Service users is among the assets transferred.</p>
                  </li>
                  <br>
                  <li>
                  <p><strong>For other purposes</strong>: We may use Your information for other purposes, such as data analysis, identifying usage trends, determining the effectiveness of our promotional campaigns and to evaluate and improve our Service, products, services, marketing and your experience.</p>
                  </li>
                  <br>
                </ul>
                <p>We may share Your personal information in the following situations:</p>
                <br>
                <ul>
                  <li><p><strong>With Service Providers:</strong> We may share Your personal information with Service Providers to monitor and analyze the use of our Service,  to contact You.</p></li>
                  <br>
                  <li><p><strong>For business transfers:</strong> We may share or transfer Your personal information in connection with, or during negotiations of, any merger, sale of Company assets, financing, or acquisition of all or a portion of Our business to another company.</p></li>
                  <br>
                  <li><p><strong>With Affiliates:</strong> We may share Your information with Our affiliates, in which case we will require those affiliates to honor this Privacy Policy. Affiliates include Our parent company and any other subsidiaries, joint venture partners or other companies that We control or that are under common control with Us.</p></li>
                  <br>
                  <li><p><strong>With business partners:</strong> We may share Your information with Our business partners to offer You certain products, services or promotions.</p></li>
                  <br>
                  <li><p><strong>With other users:</strong> when You share personal information or otherwise interact in the public areas with other users, such information may be viewed by all users and may be publicly distributed outside.</p></li>
                  <br>
                  <li><p><strong>With Your consent</strong>: We may disclose Your personal information for any other purpose with Your consent.</li>
                  <br>
                </ul>
                <h2>Retention of Your Personal Data</h2>
                <br>
                <p>The Company will retain Your Personal Data only for as long as is necessary for the purposes set out in this Privacy Policy. We will retain and use Your Personal Data to the extent necessary to comply with our legal obligations (for example, if we are required to retain your data to comply with applicable laws), resolve disputes, and enforce our legal agreements and policies.</p>
                <br>
                <p>The Company will also retain Usage Data for internal analysis purposes. Usage Data is generally retained for a shorter period of time, except when this data is used to strengthen the security or to improve the functionality of Our Service, or We are legally obligated to retain this data for longer time periods.</p>
                <br>
                <h2>Transfer of Your Personal Data</h2>
                <br>
                <p>Your information, including Personal Data, is processed at the Company's operating offices and in any other places where the parties involved in the processing are located. It means that this information may be transferred to — and maintained on — computers located outside of Your state, province, country or other governmental jurisdiction where the data protection laws may differ than those from Your jurisdiction.</p>
                <br>
                <p>Your consent to this Privacy Policy followed by Your submission of such information represents Your agreement to that transfer.</p>
                <br>
                <p>The Company will take all steps reasonably necessary to ensure that Your data is treated securely and in accordance with this Privacy Policy and no transfer of Your Personal Data will take place to an organization or a country unless there are adequate controls in place including the security of Your data and other personal information.</p>
                <br>
                <h2>Disclosure of Your Personal Data</h2>
                <br>
                <h3>Business Transactions</h3>
                <br>
                <p>If the Company is involved in a merger, acquisition or asset sale, Your Personal Data may be transferred. We will provide notice before Your Personal Data is transferred and becomes subject to a different Privacy Policy.</p>
                <br>
                <h3>Law enforcement</h3>
                <br>
                <p>Under certain circumstances, the Company may be required to disclose Your Personal Data if required to do so by law or in response to valid requests by public authorities (e.g. a court or a government agency).</p>
                <br>
                <h3>Other legal requirements</h3>
                <br>
                <p>The Company may disclose Your Personal Data in the good faith belief that such action is necessary to:</p>
                <br>
                <ul>
                  <li>Comply with a legal obligation</li>
                  <br>
                  <li>Protect and defend the rights or property of the Company</li>
                  <br>
                  <li>Prevent or investigate possible wrongdoing in connection with the Service</li>
                  <br>
                  <li>Protect the personal safety of Users of the Service or the public</li>
                  <br>
                  <li>Protect against legal liability</li>
                  <br>
                </ul>
                <h2>Security of Your Personal Data</h2>
                <br>
                <p>The security of Your Personal Data is important to Us, but remember that no method of transmission over the Internet, or method of electronic storage is 100% secure. While We strive to use commercially acceptable means to protect Your Personal Data, We cannot guarantee its absolute security.</p>
                <br>
                <h1>Children's Privacy</h1>
                <br>
                <p>Our Service does not address anyone under the age of 13. We do not knowingly collect personally identifiable information from anyone under the age of 13. If You are a parent or guardian and You are aware that Your child has provided Us with Personal Data, please contact Us. If We become aware that We have collected Personal Data from anyone under the age of 13 without verification of parental consent, We take steps to remove that information from Our servers.</p>
                <br>
                <p>If We need to rely on consent as a legal basis for processing Your information and Your country requires consent from a parent, We may require Your parent's consent before We collect and use that information.</p>
                <br>
                <h1>Links to Other Websites</h1>
                <br>
                <p>Our Service may contain links to other websites that are not operated by Us. If You click on a third party link, You will be directed to that third party's site. We strongly advise You to review the Privacy Policy of every site You visit.</p>
                <br>
                <p>We have no control over and assume no responsibility for the content, privacy policies or practices of any third party sites or services.</p>
                <br>
                <h1>Changes to this Privacy Policy</h1>
                <br>
                <p>We may update Our Privacy Policy from time to time. We will notify You of any changes by posting the new Privacy Policy on this page.</p>
                <br>
                <p>We will let You know via email and/or a prominent notice on Our Service, prior to the change becoming effective and update the &quot;Last updated&quot; date at the top of this Privacy Policy.</p>
                <br>
                <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
                <br>
                <h1>Contact Us</h1>
                <br>
                <p>If you have any questions about this Privacy Policy, You can contact us:</p>
                <br>
                <ul>
                  <li><p>By visiting this page on our website: <a href="www.creditpad.com/index.php#privacy-policy-section" rel="external nofollow noopener" target="_blank">www.creditpad.com/index.php#privacy-policy-section</a></p></li>
                </ul>
              </section>
              <span id="contact-us-section" class="offsetElement"></span>
              <section class="guide-section" data-id="#contact-us-section">
                <p>Contact Us</p>
                <br>
                <p>For more inquiries, comments, and suggestions, feel free to message us through the contact form below.</p>
                <div id="email-status-message-div">

                </div>
                <form id="contact-us-form">
                  <label for="contact-email-address" class="field-name">Your email address</label>
                  <input type="email" id="contact-email-address" class="field" placeholder="required" required>
                  <label for="contact-email-subject" class="field-name">Subject</label>
                  <input type="text" id="contact-email-subject" class="field" placeholder="required" required>
                  <label for="contact-email-message" class="field-name">Message</label>
                  <textarea id="contact-email-message" class="field" rows="7" placeholder="required" required></textarea>
                  <button id="send-email-button" class="button" type="button" onclick="sendEmail()">Send</button>
                </form>
              </section>
          </div>
      </main>
      <div id="extra">
        <div id="jump-buttons-div">
          <button type="button" id="scroll-jump-up-button" class="material-icons gray-button jump-button" onclick="jumpTop()">arrow_upward<a href="#guide-content"></a></button>
          <button type="button" id="scroll-jump-down-button" class="material-icons gray-button jump-button" onclick="jumpBottom()">arrow_downward<a href="#guide-content"></a></button>
        </div>
      </div>
    </div>
    <footer>
      <a href="
      <?php if (isset($_SESSION['ownerLoggedIn'])) {
          echo 'customers.php';
      } elseif (isset($_SESSION['customerLoggedIn'])) {
          echo 'customer-home.php';
      } elseif (isset($_SESSION['adminLoggedIn'])) {
          echo 'applicants.php';
      } else {
          echo 'login.php';
      } ?>" id="footer-website-name">Credit Pad</a>
      <a href="#about-section" class="guide-link" onclick="makeActive(1)">About</a>
      <a href="#terms-of-use-section" class="guide-link" onclick="makeActive(2)">Terms of Use</a>
      <a href="#privacy-policy-section" class="guide-link" onclick="makeActive(3)">Privacy Policy</a>
      <a href="#contact-us-section" class="guide-link" onclick="makeActive(4)">Contact Us</a>
      <div id="external-social-links">
        <a href="#"><img src="images/facebook.png" alt=""></a>
        <a href="#"><img src="images/twitter.png" alt=""></a>
        <a href="#"><img src="images/github.png" alt=""></a>
        <a href="#"><img src="images/paypal.png" alt=""></a>
      </div>
      <p id="copyright"></p>
    </footer>
    <script type="text/javascript" src="jquery.js"></script>
    <script>
      function jumpTop(){
        let navItem = document.querySelector(".navItem");
        navItem.click(navItem);
        navItem.querySelector("a").click();
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
      }

      function jumpBottom(){
        let vh = document.documentElement.clientHeight;
        let y = document.body.scrollHeight - vh - 250 - 50;
        window.scrollTo(0, y);
        let navItem = document.querySelector(".navItem:last-of-type");
        navItem.click(navItem);
        navItem.querySelector("a").click();
      }
      
      window.onscroll = () => {
        let current = "";
        let sections = document.querySelectorAll("section");
        sections.forEach((section) => {
          let sectionTop = section.offsetTop;
          if (pageYOffset >= sectionTop) current = section.getAttribute("data-id");

          let navItems = document.querySelectorAll(".navItem");
          navItems.forEach((navItem) => {
            navItem.classList.remove("selected-navbar-item");
            if (navItem.querySelector("a").getAttribute("href") == current) makeActive(navItem);
          });
        });
        if (current == "") $(".navItem:first-of-type").addClass("selected-navbar-item");
      }

      function sendEmail(){
        $("#email-status-message-div").empty();
        let contactAddress = $("#contact-email-address").val();
        let contactSubject = $("#contact-email-subject").val();
        let contactMessage = $("#contact-email-message").val();
        $.ajax({
          url: "submit-email.php",
          type: "post",
          dataType: "json",
          data: {contactAddress : contactAddress, contactSubject : contactSubject, contactMessage : contactMessage},
          success: function (data){
            let emailStatusMessage = document.createElement("div");
            emailStatusMessage.id = "email-status-message";
            emailStatusMessage.addEventListener("click", closeStatusMessage);
            document.querySelector("#email-status-message-div").appendChild(emailStatusMessage);
            $("#email-status-message").text(data[1]);
            if (data[0] == "success") {
              $("#email-status-message").css("background-color", "#53b05032");
              $("#email-status-message").css("color", "#108531");
            }
            else {
              $("#email-status-message").css("background-color", "#b0505032");
              $("#email-status-message").css("color", "#851010");
            }
          }
        })
      }

      function closeStatusMessage(){
        $("#email-status-message").remove();   
      } 

      $(document).click(function(){
        if (!$("#dropdown-menu").hasClass("hidden-item")) {
          document.getElementById("dropdown-menu").classList.add("hidden-item");
          $("#dropdown-button span").text("arrow_drop_down");
        }
      });

      $("#dropdown-button").click(function(e){ // ignore clicks inside delete item popup
        e.stopPropagation();
      });
      
      $("#dropdown-menu a").click(function(e){ // ignore clicks inside delete item popup
        e.stopPropagation();
      });

      function toggleAccountOptions(){
        if ($("#dropdown-menu").hasClass("hidden-item")) {
          $("#dropdown-menu").removeClass("hidden-item");
          $("#dropdown-menu").addClass("container");
        }
        else {
          $("#dropdown-menu").addClass("hidden-item");
          $("#dropdown-menu").removeClass("container");
        }
        let arrow = $("#dropdown-button span").text();
        (arrow == "arrow_drop_down")? $("#dropdown-button span").text("arrow_drop_up") : $("#dropdown-button span").text("arrow_drop_down");
      }

      function makeActive(element){
        $(".selected-navbar-item").removeClass("selected-navbar-item");
        if (Number.isInteger(element)){
          $(".navItem:nth-of-type(" + element + ")").addClass("selected-navbar-item");
          if (element == 1) jumpTop();
          else if (element == 4) jumpBottom();
        }
        else element.classList.add("selected-navbar-item");
      }

      $(document).ready(function () {
        $("#copyright").html("Copyright " + "&copy; " + new Date().getFullYear() + " Credit Pad");
        let section = window.location.hash;
        if (section == "") makeActive(document.querySelector(".navItem"));
        else {
          let navItems = document.querySelectorAll(".navItem");
          navItems.forEach(function (item, index, array){
            if (item.getElementsByTagName("a")[0].href.includes(section)){
              if (index == 0) jumpTop();
              else if (index == 3) jumpBottom();
              else makeActive(item); 
            } 
          });
        }
      });
    </script>
    </body>
</html>