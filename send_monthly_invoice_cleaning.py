import smtplib
import os
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from email.mime.base import MIMEBase
from email import encoders
import glob
import re
from datetime import datetime

# Function to find the two most recent files based on a pattern
def find_most_recent_files(directory, pattern):
    files = glob.glob(os.path.join(directory, pattern))
    # Extracting the timestamp and sorting the files
    files.sort(key=lambda x: datetime.strptime(re.search(r'\d{4}-\d{2}-\d{2}-\d{4}', x).group(), '%Y-%m-%d-%H%M'), reverse=True)
    return files[:2] if len(files) >= 2 else files

# Directory and patterns
directory = '/home/aag/OneDrive/Appenzell/AppenNetwork/ScriptsAppenlinux23/abrechnungen'
pattern1 = 'last_month_each_service_compact_*.html'
pattern2 = 'last_month_salary_*.html'

# Find the most recent files
recent_files1 = find_most_recent_files(directory, pattern1)
recent_files2 = find_most_recent_files(directory, pattern2)

# Email credentials and setup
user_id = "***"
password = "***"
sender_email = "adrianoaguzzi@bluewin.ch"
receiver_emails = ["adriano.aguzzi@usz.ch", "adrianoaguzzi@bluewin.ch"]
subject = "Monatsabrechnung Frau Signer Appenzell"

# Create the email
message = MIMEMultipart()
message["From"] = sender_email
message["To"] = ", ".join(receiver_emails)
message["Subject"] = subject

# Attach the files
for file_path in recent_files1 + recent_files2:
    with open(file_path, "r") as file:
        part = MIMEText(file.read(), "html")
        part.add_header('Content-Disposition', 'attachment', filename=os.path.basename(file_path))
        message.attach(part)

# Send the email
try:
    server = smtplib.SMTP_SSL("smtpauths.bluewin.ch", 465)
    server.login(user_id, password)
    server.sendmail(sender_email, receiver_emails, message.as_string())
    server.quit()
    print("Email sent successfully")
except Exception as e:
    print(f"Error: {e}")
