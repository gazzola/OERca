#!/usr/bin/perl

use strict;
use Switch;
use Email::Valid;
use Mail::Sendmail;
use DBI;

## --------- Change these values to match the server settings --
my $db_name = '';  # database name
my $db_host = '';  # database host name
my $db_user = '';  # database user name
my $db_pass = '';  # database password
my $site_url = ''; # e.g. http://bezak.umms.med.umich.edu/oer/


## ----------- Do not edit below this line unless you know what you're doing --

# check command line arguments 
if (!check_args($ARGV[0])) { usage(); } 

# determine which emails to send
my @time = localtime(time); 
my @months = qw( Jan Feb Mar Apr May Jun Jul Aug Sep Oct Nov Dec );

# use one db connection
my $dbh = DBI->connect("DBI:mysql:$db_name:$db_host",$db_user,$db_pass) or 
						die "Cannot connect to OER database: ".DBI->errstr;

# send eamils
switch ($ARGV[0]) {
	case /dscribe(1|2)|instructor/ { digest($ARGV[0]); } 
	else { usage(); }
}

# close db handle
$dbh->disconnect;


# compose a digest for a particular role 
sub digest
{
	my $type = shift; 
	my $subject = '[OER NOTICE] Action Items for '.$time[3].' '.
							  $months[$time[4]].', '.($time[5]+1900);

	my $mail = queue($type);

	if ($mail != 0) {
			my %sendlist;

			foreach my $m (@{$mail}) {
					if (! exists $sendlist{$m->[2]}) { $sendlist{$m->[2]} = {}; } 
					if (! exists $sendlist{$m->[2]}{$m->[1]}) { $sendlist{$m->[2]}{$m->[1]} = {}; } 
					if (! exists $sendlist{$m->[2]}{$m->[1]}{$m->[5]}) { 
							$sendlist{$m->[2]}{$m->[1]}{$m->[5]} = {}; } 
					if (! exists $sendlist{$m->[2]}{$m->[1]}{$m->[5]}{$m->[6]}) { 
							$sendlist{$m->[2]}{$m->[1]}{$m->[5]}{$m->[6]} = (); } 
					push @{$sendlist{$m->[2]}{$m->[1]}{$m->[5]}{$m->[6]}}, $m;	
			}

			while (my ($to_id, $receivers) = each(%sendlist)) {
					my @to_user = get_user_by_id($to_id);
					my $msg = template('intro');
					$msg =~ s/{TO_NAME}/$to_user[1]/g; 
				  my %update_candidates = ();

					while(my ($from_id, $courses) = each(%{$receivers})) {
							  my @from_user = get_user_by_id($from_id);
								$msg .= template('body1');
								$msg =~ s/{FROM_NAME}/$from_user[1]/g;
								$msg =~ s/{FROM_ROLE}/$from_user[5]/g;

								while(my ($cid, $materials) = each(%{$courses})) {
											my $cname = (course_title($cid))[0];	
											$msg .= template('body2');
										  $msg =~ s/{CNAME}/$cname/g;

											my $items = '';
											while(my ($mid, $messages) = each(%{$materials})) {
														my $mname = (material_name($mid))[0];
														my %done = ();

                            foreach my $m (@{$messages}) { 
																  $update_candidates{$m->[0]} = 1;																																	 
																  if (!exists $done{$mname}) {
																		 switch($m->[3]) {
																			  case 'dscribe1_to_instructor' { $items .= "\n\t$mname -- ".
			                                        $site_url."materials/askforms/$cid/$mid" }
			                                  case 'dscribe1_to_dscribe2'   { $items .= "\n\t$mname -- ".
			                                        $site_url."materials/askforms/$cid/$mid"}
			                                  case 'dscribe2_to_dscribe1'   { $items .= "\n\t$mname -- ".
			                                        $site_url."materials/askforms/$cid/$mid/aitems/dscribe2"}
			                                  case 'instructor_to_dscribe1' { $items .= "\n\t$mname -- ".
			                                        $site_url."materials/askforms/$cid/$mid/done/instructor"}
																			  else {}
																		 }
																 		 $done{$mname} = 1;
																  }
                            }
											}
											$msg =~ s/{ITEMS}/$items/g;
								}
					}
		  		$msg .= template('footer');

					if (email($to_user[4], $subject, $msg)) {
							foreach my $id (keys %update_candidates) {
										update_queue($id);
							}
					}
			}
	}
}

# return the title of a course
sub course_title
{
	my $cid = shift;
	my @res = ();
	my $sql = "SELECT CONCAT(number,' ',title) FROM ocw_courses WHERE id=$cid";
	my $sth = $dbh->prepare($sql);
  $sth->execute;
	if ($sth->rows > 0) { @res = $sth->fetchrow_array; }
	$sth->finish;
	return @res; 
}

# return the name of a material
sub material_name
{
	my $mid = shift;
	my @res = ();
	my $sql = "SELECT name FROM ocw_materials WHERE id=$mid";
	my $sth = $dbh->prepare($sql);
  $sth->execute;
	if ($sth->rows > 0) { @res = $sth->fetchrow_array; }
	$sth->finish;
	return @res; 
}

# return a user's details
sub get_user_by_id
{
	my $uid = shift;
	my @res = ();
	my $sql = "SELECT * FROM ocw_users WHERE id=$uid";
	my $sth = $dbh->prepare($sql);
  $sth->execute;
	if ($sth->rows > 0) { @res = $sth->fetchrow_array; }
	$sth->finish;
	return @res; 
}

# get the list of unsent emails
sub queue
{
	my $type = shift; 
	my @emails = ();

	my $sql = "SELECT * 
							 FROM ocw_postoffice 
	            WHERE msg_type LIKE '\%to_$type'
							  AND sent = 'no'";
	my $sth = $dbh->prepare($sql);	
  $sth->execute;

	if ($sth->rows > 0) {	
			while(my @row = $sth->fetchrow_array) {
				push @emails, [@row];
			}
	}
	$sth->finish;

	return ($#emails==0) ? 0 : \@emails;
}

# update email queue: mark mails as sent
sub update_queue
{
	my $id = shift;
	my $sql = "UPDATE ocw_postoffice SET sent='yes' WHERE id=$id";
	my $sth = $dbh->prepare($sql);
  $sth->execute;
	$sth->finish;
}

# return templates for the email message
sub template
{
	my $type = shift;
	my %template = (
			'intro'=> "{TO_NAME},\n\nYou have ASK Form items from the following people that need your attention:\n\n",
			'body1' => "{FROM_NAME} ({FROM_ROLE}):\n\n",
			'body2' => " Course: {CNAME}{ITEMS}\n\n\n",
			'footer' => "Thank you.\n\nOER Tool\n\nps: Don't reply to this email -- it will go no where :)"
	);
	return $template{$type};
}

# send email
sub email
{
	my $to_email = shift;
	my $subject = shift;
	my $msg = shift;

	unless (Email::Valid->address($to_email)) {
		return 0;
	} else {
		my %mail = (
			To => $to_email,
		 	From	=> "OER Tool Notifier <nobody\@umich.edu>", 
			Subject => $subject,
			Message => $msg,
		);
  	$mail{Smtp} = 'mail-relay.itd.umich.edu';
		sendmail(%mail) or die $Mail::Sendmail::error;
	}
	return 1;
}

# make sure command line arguments make sense
sub check_args
{
	my $arg = shift;
	return ($arg eq 'dscribe1' || 
					$arg eq 'dscribe2' || 
					$arg eq 'instructor') ? 1 : 0;	
}

# print out usage summary
sub usage
{
	print "\nUSAGE: $0 <receiver role>\n\n";
	print "<receiver role> = dscribe1|dscribe2|instructor\n\n";
	exit(0);
}
