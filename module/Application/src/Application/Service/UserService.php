<?php
/**
 * Created by PhpStorm.
 * User: Ярослав
 * Date: 28.02.2016
 * Time: 20:00
 */

namespace Application\Service;
use Application\Service\EntityServiceAbstract;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\EntityRepository;

class UserService extends EntityServiceAbstract {

    protected $entityManager = null;
    protected $viewHelper = null;
	
    public function save($user)
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
	
	public function updateById($user_id)
    {
		$user = $this->getEntityManager()->getRepository('\Application\Entity\User')->findOneBy(array('id' => $user_id));
		$user->setStatus(\Application\Entity\User::STATUS_ACTIVE);
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
	
	public function setUserActive($hashcode){
		$qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('u')
            ->from('\Application\Entity\User', 'u')
            ->andWhere('MD5(CONCAT(u.email,u.username,u.id,u.password)) = :md5')
            ->setParameter('md5', $hashcode)
			->andWhere('u.status = :status')
            ->setParameter('status', \Application\Entity\User::STATUS_NONACTIVE);
			
        $query = $qb->getQuery();
        $images_array = $query->getScalarResult();
		
		if(isset($images_array[0]['u_id'])){
			$user_id = $images_array[0]['u_id'];
		}
		else{
			$user_id = null;
		}
		
		return $user_id;
		
	}
	
	public function sendConfirmationEmail($email,$username,$id,$password,$server_url){
		$hash_string = md5($email . $username . $id . $password);
		$link = $server_url . dirname($_SERVER['PHP_SELF']) . '/api/registerconfirm/' . $hash_string;
		
		$recipient="iopiopi@localhost"; //  $email
		$subject="Test Email";
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$mail_body = '
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional //EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head>

						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
						<!--[if !mso]><!--><meta http-equiv="X-UA-Compatible" content="IE=edge" /><!--<![endif]-->
						<meta name="viewport" content="width=device-width" />
						<title> </title>
						<style type="text/css">
					.wrapper a:hover {
					  text-decoration: none !important;
					}
					.btn a:hover,
					.footer__links a:hover {
					  opacity: 0.8;
					}
					.wrapper .footer__share-button:hover {
					  color: #ffffff !important;
					  opacity: 0.8;
					}
					a[x-apple-data-detectors] {
					  color: inherit !important;
					  text-decoration: none !important;
					  font-size: inherit !important;
					  font-family: inherit !important;
					  font-weight: inherit !important;
					  line-height: inherit !important;
					}
					.column {
					  padding: 0;
					  text-align: left;
					  vertical-align: top;
					}
					.mso .font-avenir,
					.mso .font-cabin,
					.mso .font-open-sans,
					.mso .font-ubuntu {
					  font-family: sans-serif !important;
					}
					.mso .font-bitter,
					.mso .font-merriweather,
					.mso .font-pt-serif {
					  font-family: Georgia, serif !important;
					}
					.mso .font-lato,
					.mso .font-roboto {
					  font-family: Tahoma, sans-serif !important;
					}
					.mso .font-pt-sans {
					  font-family: "Trebuchet MS", sans-serif !important;
					}
					.mso .footer p {
					  margin: 0;
					}
					@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2/1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
					  .fblike {
						background-image: url(https://i7.createsend1.com/static/eb/master/13-the-blueprint-3/images/fblike@2x.png) !important;
					  }
					  .tweet {
						background-image: url(https://i8.createsend1.com/static/eb/master/13-the-blueprint-3/images/tweet@2x.png) !important;
					  }
					  .linkedinshare {
						background-image: url(https://i10.createsend1.com/static/eb/master/13-the-blueprint-3/images/lishare@2x.png) !important;
					  }
					  .forwardtoafriend {
						background-image: url(https://i9.createsend1.com/static/eb/master/13-the-blueprint-3/images/forward@2x.png) !important;
					  }
					}
					@media only screen and (max-width: 620px) {
					  .wrapper .size-18,
					  .wrapper .size-20 {
						font-size: 17px !important;
						line-height: 26px !important;
					  }
					  .wrapper .size-22 {
						font-size: 18px !important;
						line-height: 26px !important;
					  }
					  .wrapper .size-24 {
						font-size: 20px !important;
						line-height: 28px !important;
					  }
					  .wrapper .size-26 {
						font-size: 22px !important;
						line-height: 31px !important;
					  }
					  .wrapper .size-28 {
						font-size: 24px !important;
						line-height: 32px !important;
					  }
					  .wrapper .size-30 {
						font-size: 26px !important;
						line-height: 34px !important;
					  }
					  .wrapper .size-32 {
						font-size: 28px !important;
						line-height: 36px !important;
					  }
					  .wrapper .size-34,
					  .wrapper .size-36 {
						font-size: 30px !important;
						line-height: 38px !important;
					  }
					  .wrapper .size-40 {
						font-size: 32px !important;
						line-height: 40px !important;
					  }
					  .wrapper .size-44 {
						font-size: 34px !important;
						line-height: 43px !important;
					  }
					  .wrapper .size-48 {
						font-size: 36px !important;
						line-height: 43px !important;
					  }
					  .wrapper .size-56 {
						font-size: 40px !important;
						line-height: 47px !important;
					  }
					  .wrapper .size-64 {
						font-size: 44px !important;
						line-height: 50px !important;
					  }
					  .divider {
						Margin-left: auto !important;
						Margin-right: auto !important;
					  }
					  .btn a {
						display: block !important;
						font-size: 14px !important;
						line-height: 24px !important;
						padding: 12px 10px !important;
						width: auto !important;
					  }
					  .btn--shadow a {
						padding: 12px 10px 13px 10px !important;
					  }
					  .image img {
						height: auto;
						width: 100%;
					  }
					  .layout,
					  .column,
					  .preheader__webversion,
					  .header td,
					  .footer,
					  .footer__left,
					  .footer__right,
					  .footer__inner {
						width: 320px !important;
					  }
					  .preheader__snippet,
					  .layout__edges {
						display: none !important;
					  }
					  .preheader__webversion {
						text-align: center !important;
					  }
					  .header__logo {
						Margin-left: 20px;
						Margin-right: 20px;
					  }
					  .layout--full-width {
						width: 100% !important;
					  }
					  .layout--full-width tbody,
					  .layout--full-width tr {
						display: table;
						Margin-left: auto;
						Margin-right: auto;
						width: 320px;
					  }
					  .column,
					  .layout__gutter,
					  .footer__left,
					  .footer__right {
						display: block;
						Float: left;
					  }
					  .footer__inner {
						text-align: center;
					  }
					  .footer__links {
						Float: none;
						Margin-left: auto;
						Margin-right: auto;
					  }
					  .footer__right p,
					  .footer__share-button {
						display: inline-block;
					  }
					  .layout__gutter {
						font-size: 20px;
						line-height: 20px;
					  }
					  .layout--no-gutter.layout--has-border:not(.layout--full-width),
					  .layout--has-gutter.layout--has-border .column__background {
						width: 322px !important;
					  }
					  .layout--has-gutter.layout--has-border {
						left: -1px;
						position: relative;
					  }
					}
					@media only screen and (max-width: 320px) {
					  .border {
						display: none;
					  }
					  .layout--no-gutter.layout--has-border:not(.layout--full-width),
					  .layout--has-gutter.layout--has-border .column__background {
						width: 320px !important;
					  }
					  .layout--has-gutter.layout--has-border {
						left: 0 !important;
					  }
					}
					</style>
						
					  <style type="text/css">
					body,.wrapper{background-color:#fbfbfb}.wrapper h1{color:#565656;font-size:26px;line-height:34px}.wrapper h1{}.wrapper h1{font-family:Avenir,sans-serif}.mso .wrapper h1{font-family:sans-serif !important}.wrapper h2{color:#555;font-size:20px;line-height:28px}.wrapper h3{color:#555;font-size:16px;line-height:24px}.wrapper a{color:#41637e}.wrapper a:hover{color:#1e2e3b !important}@media only screen and (max-width: 620px){.wrapper h1{}.wrapper h1{font-size:22px;line-height:31px}.wrapper h2{}.wrapper h2{font-size:17px;line-height:26px}.wrapper h3{}.wrapper p{}}.column,.column__background td{color:#565656;font-size:14px;line-height:21px}.column,.column__background td{font-family:Georgia,serif}.border{background-color:#c8c8c8}.layout--no-gutter.layout--has-border:not(.layout--full-width),.layout--has-gutter.layout--has-border 
					.column__background,.layout--full-width.layout--has-border{border-top:1px solid #c8c8c8;border-bottom:1px solid #c8c8c8}.wrapper blockquote{border-left:4px solid #c8c8c8}.divider{background-color:#c8c8c8}.wrapper .btn a{color:#fff}.wrapper .btn a{font-family:Georgia,serif}.wrapper .btn a:hover{color:#fff !important}.btn--flat a,.btn--shadow a,.btn--depth a{background-color:#41637e}.btn--ghost a{border:1px solid #41637e}.preheader--inline,.footer__left{color:#999}.preheader--inline,.footer__left{font-family:Georgia,serif}.wrapper .preheader--inline a,.wrapper .footer__left a{color:#999}.wrapper .preheader--inline a:hover,.wrapper .footer__left a:hover{color:#999 !important}.header__logo{color:#41637e}.header__logo{font-family:Avenir,sans-serif}.mso .header__logo{font-family:sans-serif !important}.wrapper .header__logo a{color:#41637e}.wrapper .header__logo a:hover{color:#1e2e3b 
					!important}.footer__share-button{background-color:#7e7e7e}.footer__share-button{font-family:Georgia,serif}.layout__separator--inline{font-size:20px;line-height:20px;mso-line-height-rule:exactly}
					</style><meta name="robots" content="noindex,nofollow" />
					<meta property="og:title" content="My First Campaign" />
					</head>
					<!--[if mso]>
					  <body class="mso">
					<![endif]-->
					<!--[if !mso]><!-->
					  <body class="full-padding" style="margin: 0;padding: 0;-webkit-text-size-adjust: 100%;background-color: #fbfbfb;">
					<!--<![endif]-->
						<div class="wrapper" style="background-color: #fbfbfb;">
						  <table style="border-collapse: collapse;table-layout: fixed;color: #999;font-family: Georgia,serif;" align="center">
							<tbody><tr>
							  <td class="preheader__snippet" style="padding: 10px 0 5px 0;vertical-align: top;" width="300">
								
							  </td>
							  <td class="preheader__webversion" style="text-align: right;padding: 10px 0 5px 0;vertical-align: top;" width="300">
								
							  </td>
							</tr>
						  </tbody></table>
						  <table class="header" style="border-collapse: collapse;table-layout: fixed;Margin-left: auto;Margin-right: auto;" align="center">
							<tbody><tr>
							  <td style="padding: 0;" width="600">
								<div class="header__logo emb-logo-margin-box" style="font-size: 26px;line-height: 32px;Margin-top: 6px;Margin-bottom: 20px;color: #41637e;font-family: Avenir,sans-serif;">
								  <div class="logo-center" style="font-size:0px !important;line-height:0 !important;" align="center" id="emb-email-header"><img style="height: auto;width: 100%;border: 0;max-width: 96px;" src="images/logo.png" alt="" width="96" height="82" /></div>
								</div>
							  </td>
							</tr>
						  </tbody></table>
						  <table class="layout layout--no-gutter" style="border-collapse: collapse;table-layout: fixed;Margin-left: auto;Margin-right: auto;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #a3dbf7;" align="center">
							<tbody><tr>
							  <td class="column" style="padding: 0;text-align: left;vertical-align: top;color: #565656;font-size: 14px;line-height: 21px;font-family: Georgia,serif;" width="600">
						
								<div style="Margin-left: 20px;Margin-right: 20px;Margin-top: 24px;Margin-bottom: 24px;">
						  
					<h1 style="Margin-top: 0;Margin-bottom: 0;font-style: normal;font-weight: normal;color: #565656;font-size: 26px;line-height: 34px;font-family: Avenir,sans-serif;">
					<span style="color:#fbfbfb">Здравствуйте, ' . $username . '. </span></h1><p style="Margin-top: 20px;Margin-bottom: 0;">
					<span style="color:#fbfbfb">Спасибо за регистрацию на нашем сервисе. Для активирования Вашего аккаунта пройдите по ссылке:</span></p><p style="Margin-top: 20px;Margin-bottom: 0;">&nbsp;</p>
					<a href="' . $link . '">' . $link . '</a><p style="Margin-top: 20px;Margin-bottom: 0;">&nbsp;</p>
						</div>
						
							  </td>
							</tr>
						  </tbody></table>
					  
						  <div style="font-size: 20px;line-height: 20px;mso-line-height-rule: exactly;">&nbsp;</div>
						
						  <table class="layout layout--no-gutter" style="border-collapse: collapse;table-layout: fixed;Margin-left: auto;Margin-right: auto;overflow-wrap: break-word;word-wrap: break-word;word-break: break-word;background-color: #31ace0;" align="center">
							<tbody><tr>
							  <td class="column" style="padding: 0;text-align: left;vertical-align: top;color: #565656;font-size: 14px;line-height: 21px;font-family: Georgia,serif;" width="300">
						
								<div style="Margin-left: 20px;Margin-right: 20px;Margin-top: 24px;Margin-bottom: 24px;">
						  <p style="Margin-top: 0;Margin-bottom: 0;"><strong><span style="color:#fbfbfb">&#1041;&#1072;&#1079;&#1072;&#1044;&#1058;&#1055;</span></strong></p><p style="Margin-top: 20px;Margin-bottom: 0;">bazaDTP.ru</p>
						</div>
						
							  </td>
							  <td class="column" style="padding: 0;text-align: left;vertical-align: top;color: #565656;font-size: 14px;line-height: 21px;font-family: Georgia,serif;" width="300">
						
							  </td>
							</tr>
						  </tbody></table>
					  
						  <div style="font-size: 20px;line-height: 20px;mso-line-height-rule: exactly;">&nbsp;</div>
						
						  <table class="footer" style="border-collapse: collapse;table-layout: fixed;Margin-right: auto;Margin-left: auto;border-spacing: 0;" width="600" align="center">
							<tbody><tr>
							  <td style="padding: 0 0 40px 0;">
								<table class="footer__right" style="border-collapse: collapse;table-layout: auto;border-spacing: 0;" align="right">
								  <tbody><tr>
									<td class="footer__inner" style="padding: 0;">
									  
									  
									  
									  
									</td>
								  </tr>
								</tbody></table>
								<table class="footer__left" style="border-collapse: collapse;table-layout: fixed;border-spacing: 0;color: #999;font-family: Georgia,serif;" width="400">
								  <tbody><tr>
									<td class="footer__inner" style="padding: 0;font-size: 12px;line-height: 19px;">
									  
									  <div>
										<div>&#1041;&#1072;&#1079;&#1072;&#1044;&#1058;&#1055;</div>
									  </div>
									  <div class="footer__permission" style="Margin-top: 18px;">
										
									  </div>
									  <div>
										<unsubscribe>Unsubscribe</unsubscribe>
									  </div>
									</td>
								  </tr>
								</tbody></table>
							  </td>
							</tr>
						  </tbody></table>
						  <badge>
							
						  </badge>
						</div>
					  
					</body></html>';
					
		$mail_result = mail($recipient, $subject, $mail_body, $headers);
	}
	
	public function checkLogin($username,$password)
    {
        $user = $this->getEntityManager()->getRepository('\Application\Entity\User')->findOneBy(array('username' => $username, 'password' => $password));

        return $user;
    }
	
	public function getIsUserAdmin($userId){
		
		
		$admin = $this->getEntityManager()->getRepository('\Application\Entity\Admin')->findOneBy(array('id' => $userId));
		if(!is_null($admin->getId())){
			$isAdmin = 1;
		}
		else{
			$isAdmin = 0;
		}
		
        return $isAdmin;
		
	}
	
    public function setEntityManager($em){
        $this->entityManager = $em;
        return $this;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }
	
    public function setViewHelper($sm){
        $this->viewHelper = $sm;
        return $this;
    }

    public function getViewHelper()
    {
        return $this->viewHelper;
    }
	
} 