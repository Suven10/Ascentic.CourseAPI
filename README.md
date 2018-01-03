http://localhost:8012/Ascentic/API/src/Ascentic.ProfileAPI/profiles/?skip=0&take=100&order=desc
http://localhost:8012/Ascentic/API/src/Ascentic.ProfileAPI/profile/2849DA68-6727-44D3-12DC-3DDDEF4DD5A2

http://localhost:8012/Ascentic/API/src/Ascentic.ProfileAPI/profile
{
	"gender": "Male",
	"dob": "1992-10-10",
	"email": "suvethann10@gmail.com",
	"address": "Wellawatte,Colombo-06",
	"firstName": "Suvethan",
	"lastName": "Nantha",
	"phone": "94777612091",
	"ssn":"902840474v"
}

headers - content-type : application/json

CREATE TABLE [dbo].[profile](
	[gender] [varchar](250) NULL,
	[status] [bigint] NULL,
	[dob] [varchar](250) NULL,
	[email] [varchar](250) NULL,
	[profileNo] [int] IDENTITY(1,1) NOT NULL,
	[createdDate] [datetime] NULL,
	[address] [varchar](250) NULL,
	[firstName] [varchar](250) NULL,
	[lastName] [varchar](250) NULL,
	[phone] [varchar](250) NULL,
	[guProfileId] [varchar](MAX) NULL,
	[ssn] VARCHAR(50)
 CONSTRAINT [profile_PRIMARY] PRIMARY KEY CLUSTERED 
(
	[profileNo] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON),
UNIQUE NONCLUSTERED 
(
	[email] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON)
)

GO