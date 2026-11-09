(() => {
  const body = document.body;
  const role = body.dataset.demoRole === 'admin' ? 'admin' : body.classList.contains('admin') ? 'admin' : 'client';
  const homeView = document.getElementById('view-home');
  const moduleView = document.getElementById('view-module');
  const crumb = document.querySelector('.crumbs strong');
  const menuBtn = document.getElementById('menuBtn');
  const links = Array.from(document.querySelectorAll('.sb-link'));
  if (!homeView || !moduleView) return;

  const homeTitle = document.title;
  const homeCrumb = crumb ? crumb.textContent : 'Dashboard';

  const LABELS = {
    admin: {
      'dashboard': 'home',
      'client management': 'clients',
      'company management': 'companies',
      'legal matters': 'matters',
      'corporate governance': 'governance',
      'compliance management': 'compliance',
      'cyber governance': 'cyber',
      'labor & hr governance': 'labour',
      'board decision support': 'board',
      'disciplinary & adr': 'adr',
      'legal & financial advisory': 'advisory',
      'task management': 'tasks',
      'compliance calendar': 'calendar',
      'document management': 'documents',
      'reports & analytics': 'reports',
      'service packages': 'packages',
      'billing & payments': 'billing',
      'user management': 'users',
      'messages & support': 'messages',
      'notifications': 'notifications',
      'audit logs': 'audit',
      'settings': 'settings'
    },
    client: {
      'my dashboard': 'home',
      'my company profile': 'company',
      'my legal matters': 'matters',
      'corporate governance': 'governance',
      'compliance calendar': 'calendar',
      'cyber risk management': 'cyber',
      'labor & hr compliance': 'labour',
      'board decision support': 'board',
      'disputes & adr': 'adr',
      'legal & financial advisory': 'advisory',
      'documents & downloads': 'documents',
      'reports': 'reports',
      'advisory requests': 'requests',
      'messages & support': 'messages',
      'notifications': 'notifications',
      'my subscription': 'subscription',
      'account settings': 'settings'
    }
  };

  const TONES = {
    active: 'ok', current: 'ok', compliant: 'ok', verified: 'ok', signed: 'ok', delivered: 'ok',
    included: 'ok', completed: 'ok', resolved: 'ok', online: 'ok', 'on track': 'ok',
    'in progress': 'info', 'in review': 'info', 'under review': 'info', scheduled: 'info',
    onboarding: 'info', new: 'info', unread: 'info', 'awaiting reply': 'info',
    pending: 'med', 'awaiting input': 'med', 'awaiting your input': 'med', 'awaiting information': 'med',
    'awaiting payment': 'med', 'due soon': 'med', 'review due': 'med', 'renewal due': 'med', medium: 'med',
    'action required': 'high', overdue: 'high', open: 'high', suspended: 'high', flagged: 'high',
    high: 'high', locked: 'high', failed: 'high',
    'not started': 'neutral', 'not configured': 'neutral', 'not active': 'neutral', archived: 'neutral',
    read: 'neutral', draft: 'neutral', planned: 'neutral', optional: 'neutral', low: 'low'
  };

  const ADMIN = {
    clients: {
      title: 'Client management',
      eyebrow: 'Client portfolio',
      sub: 'Registered client companies, primary contacts and onboarding progress.',
      stats: [
        ['Registered clients', '128', '+6 this month', 'i-users'],
        ['Active companies', '96', '75% of portfolio', 'i-building'],
        ['Pending onboarding', '7', '3 awaiting documents', 'i-id'],
        ['Avg. relationship', '2.4 yrs', 'since first engagement', 'i-history']
      ],
      table: {
        title: 'Client directory',
        sub: 'All registered accounts and their package status.',
        cols: ['Client', 'Primary contact', 'Package', 'Status'],
        rows: [
          [{ v: 'Delta Textiles Ltd.', meta: 'Manufacturing · 42 staff' }, 'Ayesha Rahman', 'Standard', { p: 'Active' }],
          [{ v: 'Orion Foods Ltd.', meta: 'Food processing · 118 staff' }, 'Tanvir Ahmed', 'Premium', { p: 'Active' }],
          [{ v: 'Ashford Apparels', meta: 'Garments · 64 staff' }, 'Farhana Islam', 'Essential', { p: 'Onboarding' }],
          [{ v: 'Meridian Logistics', meta: 'Logistics · 87 staff' }, 'Rafiq Chowdhury', 'Standard', { p: 'Under review' }],
          [{ v: 'Greenline Group', meta: 'Construction · 210 staff' }, 'Nusrat Jahan', 'Premium', { p: 'Active' }]
        ]
      },
      upcoming: {
        title: 'Onboarding and reviews',
        sub: 'Next client-facing tasks.',
        items: [
          ['12', 'Nov', 'Onboarding call — Ashford Apparels', 'Compliance walkthrough', 'Scheduled'],
          ['14', 'Nov', 'Quarterly account review — Delta Textiles', 'Standard package', 'Scheduled'],
          ['18', 'Nov', 'Package review — Meridian Logistics', 'Awaiting documents', 'Awaiting input']
        ]
      },
      activity: {
        title: 'Recent client activity',
        items: [
          ['Delta Textiles uploaded board minutes', '12 minutes ago · Corporate governance'],
          ['New client registered — Ashford Apparels', '3 hours ago · Client management'],
          ['Package upgraded — Orion Foods', 'Yesterday · Commercial'],
          ['Meridian Logistics invited two users', '2 days ago · User management']
        ]
      }
    },
    companies: {
      title: 'Company management',
      eyebrow: 'Entity register',
      sub: 'Client companies, entity records and profile verification status.',
      stats: [
        ['Active companies', '96', 'across 12 sectors', 'i-building'],
        ['Verified profiles', '91', '5 pending review', 'i-check'],
        ['Directors on record', '412', 'across all entities', 'i-users'],
        ['Updated this month', '18', 'company records', 'i-history']
      ],
      table: {
        title: 'Company register',
        sub: 'Entity details and current compliance standing.',
        cols: ['Company', 'Sector', 'Entity type', 'Compliance'],
        rows: [
          [{ v: 'Delta Textiles Ltd.', meta: 'Dhaka · 42 staff' }, 'Manufacturing', 'Private limited', { p: 'Compliant' }],
          [{ v: 'Orion Foods Ltd.', meta: 'Chattogram · 118 staff' }, 'Food processing', 'Private limited', { p: 'Current' }],
          [{ v: 'Ashford Apparels', meta: 'Gazipur · 64 staff' }, 'Garments', 'Private limited', { p: 'Under review' }],
          [{ v: 'Meridian Logistics', meta: 'Dhaka · 87 staff' }, 'Logistics', 'Private limited', { p: 'Action required' }],
          [{ v: 'Greenline Group', meta: 'Dhaka · 210 staff' }, 'Construction', 'Public limited', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'Record reviews',
        sub: 'Profile and entity checks.',
        items: [
          ['13', 'Nov', 'Profile verification — Ashford Apparels', 'Director records', 'In progress'],
          ['17', 'Nov', 'Entity details review — Meridian Logistics', 'Registered office change', 'Scheduled'],
          ['21', 'Nov', 'Sector review — Garments portfolio', 'Quarterly sample', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent record changes',
        items: [
          ['Delta Textiles updated director records', '1 hour ago · Company management'],
          ['Greenline Group verified profile', 'Yesterday · Verification'],
          ['Orion Foods added a branch address', '2 days ago · Company management'],
          ['Ashford Apparels submitted incorporation file', '3 days ago · Onboarding']
        ]
      }
    },
    matters: {
      title: 'Legal matters',
      eyebrow: 'Matter workspace',
      sub: 'Active engagements, next steps and matter-level progress.',
      stats: [
        ['Active matters', '37', 'across 24 clients', 'i-scale'],
        ['Awaiting client input', '6', 'needs follow-up', 'i-msg'],
        ['Hearings this month', '9', 'scheduled', 'i-gavel'],
        ['Closed this quarter', '14', 'matters completed', 'i-check']
      ],
      table: {
        title: 'Matter register',
        sub: 'Current matters and their next action.',
        cols: ['Matter', 'Client', 'Type', 'Next step', 'Status'],
        rows: [
          [{ v: 'Share transfer — family holding', meta: 'MAT-2026-014' }, 'Delta Textiles', 'Corporate', 'Board approval', { p: 'In progress' }],
          [{ v: 'VAT assessment — review', meta: 'MAT-2026-017' }, 'Orion Foods', 'Tax', 'Documentation', { p: 'Under review' }],
          [{ v: 'Supply contract review', meta: 'MAT-2026-019' }, 'Ashford Apparels', 'Commercial', 'Draft review', { p: 'In progress' }],
          [{ v: 'Employment claim — response', meta: 'MAT-2026-022' }, 'Meridian Logistics', 'Labour', 'Response draft', { p: 'Action required' }],
          [{ v: 'Arbitration — hearing', meta: 'MAT-2026-009' }, 'Greenline Group', 'Dispute', 'Hearing prep', { p: 'Scheduled' }]
        ]
      },
      upcoming: {
        title: 'Matter milestones',
        sub: 'Dates requiring preparation.',
        items: [
          ['12', 'Nov', 'Board approval — share transfer', 'Delta Textiles', 'In progress'],
          ['14', 'Nov', 'Statement of claim review', 'Greenline Group', 'In progress'],
          ['19', 'Nov', 'Response draft — employment claim', 'Meridian Logistics', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent matter activity',
        items: [
          ['Share transfer file updated', '18 minutes ago · Delta Textiles'],
          ['VAT documentation received', '2 hours ago · Orion Foods'],
          ['Draft contract shared for review', 'Yesterday · Ashford Apparels'],
          ['Hearing bundle prepared', '2 days ago · Greenline Group']
        ]
      }
    },
    governance: {
      title: 'Corporate governance',
      eyebrow: 'Board operations',
      sub: 'Board cycles, meeting records and governance actions across the portfolio.',
      stats: [
        ['Companies under governance', '64', 'active board cycles', 'i-bank'],
        ['On track', '58', 'cycles current', 'i-check'],
        ['Pending resolutions', '11', 'awaiting action', 'i-doc'],
        ['Meetings this quarter', '23', 'recorded', 'i-calendar']
      ],
      table: {
        title: 'Governance register',
        sub: 'Board cycle status by company.',
        cols: ['Company', 'Board cycle', 'Last meeting', 'Status'],
        rows: [
          [{ v: 'Delta Textiles Ltd.', meta: '5 directors' }, '2026 cycle', '14 Oct 2026', { p: 'Current' }],
          [{ v: 'Orion Foods Ltd.', meta: '7 directors' }, '2026 cycle', '02 Oct 2026', { p: 'Current' }],
          [{ v: 'Ashford Apparels', meta: '5 directors' }, 'Onboarding', '—', { p: 'Not started' }],
          [{ v: 'Meridian Logistics', meta: '6 directors' }, '2026 cycle', '28 Sep 2026', { p: 'Action required' }],
          [{ v: 'Greenline Group', meta: '9 directors' }, '2026 cycle', '09 Oct 2026', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'Board calendar',
        sub: 'Upcoming governance sessions.',
        items: [
          ['14', 'Nov', 'Board pack review — Delta Textiles', 'Q4 pack', 'In progress'],
          ['19', 'Nov', 'Board meeting — Delta Textiles', 'Share transfer', 'Scheduled'],
          ['02', 'Dec', 'AGM planning — Greenline Group', 'Agenda draft', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent governance activity',
        items: [
          ['Delta Textiles uploaded board minutes', '12 minutes ago · Governance'],
          ['Orion Foods approved Q4 pack', 'Yesterday · Board support'],
          ['Greenline Group added AGM agenda', '2 days ago · Governance'],
          ['Ashford Apparels completed onboarding checklist', '4 days ago · Onboarding']
        ]
      }
    },
    compliance: {
      title: 'Compliance management',
      eyebrow: 'Obligation tracking',
      sub: 'Compliance obligations, working windows and exception handling.',
      stats: [
        ['Compliance score', '88%', 'portfolio average', 'i-shield'],
        ['Obligations tracked', '214', 'across all clients', 'i-doc'],
        ['Due this month', '18', 'in working window', 'i-calendar'],
        ['Overdue', '3', 'needs attention', 'i-bell']
      ],
      table: {
        title: 'Obligation tracker',
        sub: 'Working windows are internal targets, not statutory advice.',
        cols: ['Obligation', 'Company', 'Owner', 'Window', 'Status'],
        rows: [
          [{ v: 'Annual return pack', meta: 'Compliance supervision' }, 'Delta Textiles', 'N. Saliheen', 'This month', { p: 'In progress' }],
          [{ v: 'VAT cycle review', meta: 'Finance coordination' }, 'Orion Foods', 'T. Chowdhury', 'This week', { p: 'Due soon' }],
          [{ v: 'Trade licence renewal file', meta: 'Corporate records' }, 'Ashford Apparels', 'A. Haque', 'Next 30 days', { p: 'Scheduled' }],
          [{ v: 'Labour register update', meta: 'HR governance' }, 'Meridian Logistics', 'S. Karim', 'Past window', { p: 'Overdue' }],
          [{ v: 'Environmental permit file', meta: 'Regulatory records' }, 'Greenline Group', 'N. Saliheen', 'Quarter end', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'Compliance reviews',
        sub: 'Upcoming internal checkpoints.',
        items: [
          ['13', 'Nov', 'VAT cycle checkpoint — Orion Foods', 'Document review', 'In progress'],
          ['18', 'Nov', 'Annual return pack — Delta Textiles', 'Draft preparation', 'Scheduled'],
          ['25', 'Nov', 'Labour register review — Meridian Logistics', 'Exception follow-up', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent compliance activity',
        items: [
          ['VAT assessment completed for Orion Foods', '1 hour ago · Compliance'],
          ['Annual return draft uploaded', 'Yesterday · Delta Textiles'],
          ['Labour register exception raised', '2 days ago · Meridian Logistics'],
          ['Trade licence file marked complete', '4 days ago · Ashford Apparels']
        ]
      }
    },
    cyber: {
      title: 'Cyber governance',
      eyebrow: 'Cyber risk oversight',
      sub: 'Cyber controls, policy currency and open findings across client companies.',
      stats: [
        ['Controls tracked', '46', 'portfolio-wide', 'i-lock'],
        ['Policies current', '39', '7 need review', 'i-shield'],
        ['Open findings', '7', 'across 5 companies', 'i-bell'],
        ['High severity', '2', 'prioritised', 'i-gavel']
      ],
      table: {
        title: 'Cyber control register',
        sub: 'Control ownership and review currency.',
        cols: ['Control', 'Company', 'Review', 'Status'],
        rows: [
          [{ v: 'Access control policy', meta: 'Policy review' }, 'Delta Textiles', 'Sep 2026', { p: 'Current' }],
          [{ v: 'Vendor risk register', meta: 'Third-party oversight' }, 'Orion Foods', 'Aug 2026', { p: 'In review' }],
          [{ v: 'Incident response plan', meta: 'Response readiness' }, 'Meridian Logistics', 'Jul 2026', { p: 'Action required' }],
          [{ v: 'Data retention schedule', meta: 'Records management' }, 'Ashford Apparels', '—', { p: 'Not started' }],
          [{ v: 'Backup testing record', meta: 'Operational resilience' }, 'Greenline Group', 'Oct 2026', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'Cyber reviews',
        sub: 'Upcoming walkthroughs and checks.',
        items: [
          ['12', 'Nov', 'Incident response walkthrough', 'Meridian Logistics', 'In progress'],
          ['20', 'Nov', 'Vendor risk review', 'Orion Foods', 'Scheduled'],
          ['27', 'Nov', 'Retention schedule workshop', 'Ashford Apparels', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent cyber activity',
        items: [
          ['Cyber risk register updated', '5 hours ago · Meridian Logistics'],
          ['Access control evidence uploaded', 'Yesterday · Delta Textiles'],
          ['Vendor review note added', '2 days ago · Orion Foods'],
          ['Backup test record filed', '4 days ago · Greenline Group']
        ]
      }
    },
    labour: {
      title: 'Labor & HR governance',
      eyebrow: 'People governance',
      sub: 'HR policies, employee matters and review currency.',
      stats: [
        ['Policies tracked', '58', 'across the portfolio', 'i-briefcase'],
        ['Current', '49', '9 need review', 'i-check'],
        ['Reviews due', '9', 'next 60 days', 'i-calendar'],
        ['Open employee matters', '4', 'with advisers', 'i-msg']
      ],
      table: {
        title: 'HR policy register',
        sub: 'Policy ownership and review status.',
        cols: ['Policy', 'Company', 'Updated', 'Status'],
        rows: [
          [{ v: 'Employment policy', meta: 'Handbook' }, 'Delta Textiles', 'Oct 2026', { p: 'Current' }],
          [{ v: 'Grievance procedure', meta: 'Handbook' }, 'Orion Foods', 'Sep 2026', { p: 'Current' }],
          [{ v: 'Working hours policy', meta: 'Handbook' }, 'Ashford Apparels', '—', { p: 'Not started' }],
          [{ v: 'Separation checklist', meta: 'HR operations' }, 'Meridian Logistics', 'Aug 2026', { p: 'In review' }],
          [{ v: 'Safety protocol', meta: 'Workplace' }, 'Greenline Group', 'Oct 2026', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'HR reviews',
        sub: 'Policy and matter checkpoints.',
        items: [
          ['13', 'Nov', 'Employment policy review — Delta Textiles', 'Draft comments', 'In progress'],
          ['20', 'Nov', 'Grievance procedure review — Orion Foods', 'Annual check', 'Scheduled'],
          ['27', 'Nov', 'HR clinic — Meridian Logistics', 'Employee matters', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent HR activity',
        items: [
          ['Employment policy draft shared', '2 hours ago · Delta Textiles'],
          ['Grievance log reviewed', 'Yesterday · Orion Foods'],
          ['Separation checklist updated', '3 days ago · Meridian Logistics'],
          ['Safety protocol filed', '5 days ago · Greenline Group']
        ]
      }
    },
    board: {
      title: 'Board decision support',
      eyebrow: 'Board services',
      sub: 'Resolutions, board packs and follow-up actions.',
      stats: [
        ['Upcoming meetings', '6', 'next 30 days', 'i-calendar'],
        ['Open resolutions', '11', 'in preparation', 'i-doc'],
        ['Packs in review', '4', 'board packs', 'i-folder'],
        ['Governance actions', '8', 'assigned', 'i-bank']
      ],
      table: {
        title: 'Resolution tracker',
        sub: 'Board decisions and preparation status.',
        cols: ['Resolution', 'Company', 'Meeting', 'Status'],
        rows: [
          [{ v: 'Share transfer approval', meta: 'Corporate' }, 'Delta Textiles', '19 Nov 2026', { p: 'In progress' }],
          [{ v: 'Annual budget adoption', meta: 'Finance' }, 'Orion Foods', '24 Nov 2026', { p: 'Scheduled' }],
          [{ v: 'Director appointment', meta: 'Governance' }, 'Ashford Apparels', '—', { p: 'Draft' }],
          [{ v: 'Related-party disclosure', meta: 'Compliance' }, 'Meridian Logistics', '27 Nov 2026', { p: 'Under review' }],
          [{ v: 'AGM agenda', meta: 'Governance' }, 'Greenline Group', '02 Dec 2026', { p: 'Scheduled' }]
        ]
      },
      upcoming: {
        title: 'Board schedule',
        sub: 'Pack and meeting preparation.',
        items: [
          ['14', 'Nov', 'Q4 board pack review — Delta Textiles', 'Final comments', 'In progress'],
          ['24', 'Nov', 'Board meeting — Orion Foods', 'Budget adoption', 'Scheduled'],
          ['02', 'Dec', 'AGM planning — Greenline Group', 'Agenda review', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent board activity',
        items: [
          ['Board resolution — share transfer shared', '1 day ago · Delta Textiles'],
          ['Budget pack comments added', '2 days ago · Orion Foods'],
          ['Director appointment draft created', '3 days ago · Ashford Apparels'],
          ['AGM agenda outline prepared', '5 days ago · Greenline Group']
        ]
      }
    },
    adr: {
      title: 'Disciplinary & ADR',
      eyebrow: 'Disputes and ADR',
      sub: 'Disciplinary proceedings, mediation and arbitration matters.',
      stats: [
        ['Open disputes', '4', 'active files', 'i-gavel'],
        ['In mediation', '2', 'sessions planned', 'i-msg'],
        ['Arbitration', '1', 'hearing preparation', 'i-bank'],
        ['Resolved this year', '6', 'closed matters', 'i-check']
      ],
      table: {
        title: 'Dispute register',
        sub: 'Forum, next step and current status.',
        cols: ['Dispute', 'Client', 'Forum', 'Next step', 'Status'],
        rows: [
          [{ v: 'Supply payment claim', meta: 'ADR-2026-004' }, 'Delta Textiles', 'Mediation', 'Position paper', { p: 'In progress' }],
          [{ v: 'Employment claim', meta: 'ADR-2026-006' }, 'Meridian Logistics', 'Labour tribunal', 'Response draft', { p: 'Action required' }],
          [{ v: 'Contract termination', meta: 'ADR-2026-007' }, 'Orion Foods', 'Negotiation', 'Counterproposal', { p: 'Under review' }],
          [{ v: 'Shareholder disagreement', meta: 'ADR-2026-002' }, 'Greenline Group', 'Arbitration', 'Hearing prep', { p: 'Scheduled' }]
        ]
      },
      upcoming: {
        title: 'ADR milestones',
        sub: 'Sessions and filing steps.',
        items: [
          ['13', 'Nov', 'Mediation session — Delta Textiles', 'Position paper', 'Scheduled'],
          ['18', 'Nov', 'Response draft — Meridian Logistics', 'Tribunal filing', 'In progress'],
          ['26', 'Nov', 'Arbitration prep — Greenline Group', 'Hearing bundle', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent ADR activity',
        items: [
          ['Mediation position paper uploaded', '3 hours ago · Delta Textiles'],
          ['Tribunal correspondence received', 'Yesterday · Meridian Logistics'],
          ['Counterproposal shared', '3 days ago · Orion Foods'],
          ['Hearing bundle outline prepared', '5 days ago · Greenline Group']
        ]
      }
    },
    advisory: {
      title: 'Legal & financial advisory',
      eyebrow: 'Advisory desk',
      sub: 'Advisory requests, assigned advisers and response progress.',
      stats: [
        ['Open requests', '12', 'across 9 clients', 'i-msg'],
        ['Average response', '1.2 days', 'this quarter', 'i-history'],
        ['Areas covered', '6', 'advisory categories', 'i-briefcase'],
        ['Resolved this month', '9', 'requests closed', 'i-check']
      ],
      table: {
        title: 'Advisory requests',
        sub: 'Current requests and ownership.',
        cols: ['Request', 'Client', 'Area', 'Adviser', 'Status'],
        rows: [
          [{ v: 'Expansion advisory', meta: 'New market entry' }, 'Delta Textiles', 'Corporate', 'N. Saliheen', { p: 'In progress' }],
          [{ v: 'Tax structuring query', meta: 'Group restructure' }, 'Orion Foods', 'Financial', 'T. Chowdhury', { p: 'Under review' }],
          [{ v: 'Contract clause query', meta: 'Supply agreement' }, 'Ashford Apparels', 'Commercial', 'S. Karim', { p: 'New' }],
          [{ v: 'Policy interpretation', meta: 'Leave entitlements' }, 'Meridian Logistics', 'Labour', 'A. Haque', { p: 'In progress' }],
          [{ v: 'Governance review', meta: 'Board charter' }, 'Greenline Group', 'Corporate', 'N. Saliheen', { p: 'Scheduled' }]
        ]
      },
      upcoming: {
        title: 'Advisory schedule',
        sub: 'Calls and delivery checkpoints.',
        items: [
          ['12', 'Nov', 'Advisory call — Delta Textiles', 'Expansion plan', 'Scheduled'],
          ['19', 'Nov', 'Request triage — open queue', 'Weekly review', 'Scheduled'],
          ['26', 'Nov', 'Governance review — Greenline Group', 'Board charter', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent advisory activity',
        items: [
          ['Expansion advisory note uploaded', '1 hour ago · Delta Textiles'],
          ['Tax query assigned to adviser', 'Yesterday · Orion Foods'],
          ['Contract clause response sent', '2 days ago · Ashford Apparels'],
          ['Governance review scheduled', '4 days ago · Greenline Group']
        ]
      }
    },
    tasks: {
      title: 'Task management',
      eyebrow: 'Firm workflow',
      sub: 'Assignments, due dates and completion status across the team.',
      stats: [
        ['Open tasks', '24', 'assigned', 'i-check'],
        ['Due this week', '9', 'in the next 7 days', 'i-calendar'],
        ['Overdue', '3', 'needs attention', 'i-bell'],
        ['Completed this month', '41', 'tasks closed', 'i-history']
      ],
      table: {
        title: 'Task board',
        sub: 'Current assignments by owner and due date.',
        cols: ['Task', 'Matter', 'Owner', 'Due', 'Priority'],
        rows: [
          [{ v: 'Board minutes — AGM pack', meta: 'Corporate governance' }, 'Delta Textiles', 'N. Saliheen', 'Today, 4:00 PM', { p: 'High' }],
          [{ v: 'VAT return review — Nov', meta: 'Compliance supervision' }, 'Orion Foods', 'T. Chowdhury', 'Today, 6:00 PM', { p: 'High' }],
          [{ v: 'Vendor contract review', meta: 'Cyber risk' }, 'Meridian Logistics', 'S. Karim', 'Tomorrow', { p: 'Medium' }],
          [{ v: 'Trade licence renewal', meta: 'Compliance supervision' }, 'Ashford Apparels', 'A. Haque', '12 Nov', { p: 'Medium' }],
          [{ v: 'Statement of claim', meta: 'Arbitration' }, 'Greenline Group', 'N. Saliheen', '14 Nov', { p: 'High' }]
        ]
      },
      upcoming: {
        title: 'Due soon',
        sub: 'Tasks in the next working window.',
        items: [
          ['12', 'Nov', 'Trade licence renewal', 'Ashford Apparels', 'In progress'],
          ['13', 'Nov', 'Mediation position paper', 'Delta Textiles', 'In progress'],
          ['14', 'Nov', 'Statement of claim', 'Greenline Group', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent task activity',
        items: [
          ['Board minutes task updated', '12 minutes ago · N. Saliheen'],
          ['VAT review marked in progress', '1 hour ago · T. Chowdhury'],
          ['Vendor contract reassigned', 'Yesterday · S. Karim'],
          ['Trade licence task completed', '2 days ago · A. Haque']
        ]
      }
    },
    calendar: {
      title: 'Compliance calendar',
      eyebrow: 'Working calendar',
      sub: 'Internal compliance and governance checkpoints across the client portfolio.',
      stats: [
        ['Items this month', '18', 'scheduled', 'i-calendar'],
        ['Due this week', '6', 'internal targets', 'i-bell'],
        ['Completed', '11', 'this month', 'i-check'],
        ['Needs review', '3', 'unassigned or overdue', 'i-doc']
      ],
      table: {
        title: 'Calendar items',
        sub: 'Internal working windows, not statutory deadlines.',
        cols: ['Item', 'Company', 'Owner', 'Window', 'Status'],
        rows: [
          [{ v: 'Annual return pack', meta: 'Compliance supervision' }, 'Delta Textiles', 'N. Saliheen', 'This month', { p: 'In progress' }],
          [{ v: 'Board meeting pack', meta: 'Governance' }, 'Delta Textiles', 'N. Saliheen', '14 Nov', { p: 'In progress' }],
          [{ v: 'VAT cycle review', meta: 'Compliance supervision' }, 'Orion Foods', 'T. Chowdhury', 'This week', { p: 'Scheduled' }],
          [{ v: 'Labour register update', meta: 'HR governance' }, 'Meridian Logistics', 'S. Karim', 'Next 30 days', { p: 'Awaiting input' }],
          [{ v: 'Trade licence file', meta: 'Corporate records' }, 'Ashford Apparels', 'A. Haque', 'Quarter end', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'Next checkpoints',
        sub: 'Dates to prepare for.',
        items: [
          ['14', 'Nov', 'Board meeting pack', 'Delta Textiles', 'In progress'],
          ['18', 'Nov', 'Annual return draft', 'Delta Textiles', 'Scheduled'],
          ['25', 'Nov', 'Labour register review', 'Meridian Logistics', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent calendar activity',
        items: [
          ['Board pack checkpoint completed', '2 hours ago · Delta Textiles'],
          ['VAT review scheduled', 'Yesterday · Orion Foods'],
          ['Labour register task assigned', '3 days ago · Meridian Logistics'],
          ['Trade licence file updated', '5 days ago · Ashford Apparels']
        ]
      }
    },
    documents: {
      title: 'Document management',
      eyebrow: 'Document library',
      sub: 'Shared documents, review status and company-level filing.',
      stats: [
        ['Documents', '1,284', 'in the library', 'i-folder'],
        ['Shared this week', '28', 'with clients', 'i-doc'],
        ['Awaiting review', '16', 'by the team', 'i-history'],
        ['Archived', '40', 'this quarter', 'i-folder']
      ],
      table: {
        title: 'Recent documents',
        sub: 'Latest uploads and review status.',
        cols: ['Document', 'Company', 'Category', 'Updated', 'Status'],
        rows: [
          [{ v: 'AGM minutes — 2026.pdf', meta: 'PDF · Governance' }, 'Delta Textiles', 'Governance', '2 hours ago', { p: 'Current' }],
          [{ v: 'Cyber risk register v3.xlsx', meta: 'XLSX · Cyber' }, 'Meridian Logistics', 'Cyber', '5 hours ago', { p: 'In review' }],
          [{ v: 'Employment policy 2026.docx', meta: 'DOCX · Labour' }, 'Orion Foods', 'Labour', 'Yesterday', { p: 'Current' }],
          [{ v: 'Due diligence report.pdf', meta: 'PDF · Corporate' }, 'Ashford Apparels', 'Corporate', 'Yesterday', { p: 'Draft' }],
          [{ v: 'Board resolution — 2026.pdf', meta: 'PDF · Governance' }, 'Delta Textiles', 'Governance', '2 weeks ago', { p: 'Signed' }]
        ]
      },
      upcoming: {
        title: 'Review queue',
        sub: 'Documents awaiting attention.',
        items: [
          ['13', 'Nov', 'Employment policy draft', 'Orion Foods', 'In review'],
          ['16', 'Nov', 'Cyber risk register v3', 'Meridian Logistics', 'In review'],
          ['20', 'Nov', 'Due diligence report', 'Ashford Apparels', 'Awaiting input']
        ]
      },
      activity: {
        title: 'Recent document activity',
        items: [
          ['AGM minutes uploaded', '2 hours ago · Delta Textiles'],
          ['Cyber risk register updated', '5 hours ago · Meridian Logistics'],
          ['Employment policy shared', 'Yesterday · Orion Foods'],
          ['Due diligence report received', 'Yesterday · Ashford Apparels']
        ]
      }
    },
    reports: {
      title: 'Reports & analytics',
      eyebrow: 'Reporting',
      sub: 'Portfolio reports, delivery status and client sharing.',
      stats: [
        ['Reports generated', '64', 'this year', 'i-chart'],
        ['Scheduled', '8', 'upcoming cycles', 'i-calendar'],
        ['This quarter', '23', 'reports prepared', 'i-doc'],
        ['Shared with clients', '19', 'delivered', 'i-msg']
      ],
      table: {
        title: 'Report register',
        sub: 'Current report cycles and delivery.',
        cols: ['Report', 'Scope', 'Period', 'Status'],
        rows: [
          [{ v: 'Quarterly compliance report', meta: 'Portfolio' }, 'All clients', 'Q3 2026', { p: 'Delivered' }],
          [{ v: 'Corporate governance summary', meta: 'Portfolio' }, 'All clients', '2026', { p: 'Delivered' }],
          [{ v: 'Legal matter progress report', meta: 'Client-level' }, 'Selected clients', 'Oct 2026', { p: 'In progress' }],
          [{ v: 'Cyber risk assessment', meta: 'Client-level' }, 'Selected clients', 'Sep 2026', { p: 'Under review' }],
          [{ v: 'Board effectiveness review', meta: 'Client-level' }, 'Selected clients', '2026', { p: 'Scheduled' }]
        ]
      },
      upcoming: {
        title: 'Report cycle',
        sub: 'Upcoming preparation dates.',
        items: [
          ['18', 'Nov', 'Quarterly compliance report', 'Q4 2026 draft', 'In progress'],
          ['02', 'Dec', 'Matter progress report', 'November cycle', 'Scheduled'],
          ['15', 'Dec', 'Governance summary', 'Year-end pack', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent reporting activity',
        items: [
          ['Q3 compliance report delivered', 'Yesterday · Portfolio'],
          ['Matter progress draft started', '2 days ago · Selected clients'],
          ['Cyber assessment reviewed', '4 days ago · Selected clients'],
          ['Governance summary shared', '1 week ago · Portfolio']
        ]
      }
    },
    packages: {
      title: 'Service packages',
      eyebrow: 'Commercial',
      sub: 'Package structure, focus areas and active client distribution.',
      stats: [
        ['Active packages', '96', 'across clients', 'i-package'],
        ['Essential', '37', 'active clients', 'i-users'],
        ['Standard', '39', 'active clients', 'i-building'],
        ['Premium', '20', 'active clients', 'i-check']
      ],
      table: {
        title: 'Package structure',
        sub: 'What each package covers. Pricing is scoped separately.',
        cols: ['Package', 'Focus', 'Active clients', 'Status'],
        rows: [
          [{ v: 'Essential', meta: 'Core governance records' }, 'Minimum governance set', '37', { p: 'Active' }],
          [{ v: 'Standard', meta: 'Governance and compliance support' }, 'Ongoing advisory support', '39', { p: 'Active' }],
          [{ v: 'Premium', meta: 'Full legal governance partnership' }, 'Dedicated adviser support', '20', { p: 'Active' }],
          [{ v: 'Custom', meta: 'Scoped engagement' }, 'Tailored scope', '—', { p: 'Not configured' }]
        ]
      },
      upcoming: {
        title: 'Package reviews',
        sub: 'Commercial checkpoints.',
        items: [
          ['14', 'Nov', 'Quarterly account review — Delta Textiles', 'Standard package', 'Scheduled'],
          ['18', 'Nov', 'Package review — Meridian Logistics', 'Scope alignment', 'Scheduled'],
          ['25', 'Nov', 'Portfolio distribution review', 'Commercial planning', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent package activity',
        items: [
          ['Orion Foods moved to Premium', 'Yesterday · Commercial'],
          ['Ashford Apparels onboarding started', '3 days ago · Commercial'],
          ['Meridian Logistics package review raised', '5 days ago · Commercial'],
          ['Package distribution updated', '1 week ago · Reporting']
        ]
      }
    },
    billing: {
      title: 'Billing & payments',
      eyebrow: 'Finance',
      sub: 'Invoice cycles, payment status and credit notes. Amounts are managed in finance.',
      stats: [
        ['Invoices issued', '24', 'this cycle', 'i-card'],
        ['Paid', '18', 'settled', 'i-check'],
        ['Awaiting payment', '5', 'follow-up needed', 'i-calendar'],
        ['Credit notes', '1', 'this cycle', 'i-doc']
      ],
      table: {
        title: 'Invoice register',
        sub: 'Cycle status by client.',
        cols: ['Invoice', 'Client', 'Period', 'Status'],
        rows: [
          [{ v: 'INV-2026-118', meta: 'Standard package' }, 'Delta Textiles Ltd.', 'Nov 2026', { p: 'Paid' }],
          [{ v: 'INV-2026-117', meta: 'Premium package' }, 'Orion Foods Ltd.', 'Nov 2026', { p: 'Paid' }],
          [{ v: 'INV-2026-116', meta: 'Essential package' }, 'Ashford Apparels', 'Nov 2026', { p: 'Awaiting payment' }],
          [{ v: 'INV-2026-115', meta: 'Standard package' }, 'Meridian Logistics', 'Oct 2026', { p: 'Paid' }],
          [{ v: 'INV-2026-114', meta: 'Premium package' }, 'Greenline Group', 'Oct 2026', { p: 'Overdue' }]
        ]
      },
      upcoming: {
        title: 'Billing calendar',
        sub: 'Upcoming finance checkpoints.',
        items: [
          ['15', 'Nov', 'Invoice cycle review', 'November batch', 'Scheduled'],
          ['22', 'Nov', 'Payment follow-up', 'Awaiting accounts', 'In progress'],
          ['01', 'Dec', 'Credit note review', 'Finance team', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent billing activity',
        items: [
          ['Invoice INV-2026-118 marked paid', '2 hours ago · Delta Textiles'],
          ['Payment reminder sent', 'Yesterday · Ashford Apparels'],
          ['Credit note recorded', '3 days ago · Greenline Group'],
          ['November invoice batch prepared', '5 days ago · Finance']
        ]
      }
    },
    users: {
      title: 'User management',
      eyebrow: 'Portal access',
      sub: 'Portal accounts, roles and access status across clients and the firm.',
      stats: [
        ['Portal users', '142', 'active accounts', 'i-users'],
        ['Client admins', '96', 'one per company', 'i-id'],
        ['Team members', '24', 'firm access', 'i-users'],
        ['Suspended', '2', 'access paused', 'i-lock']
      ],
      table: {
        title: 'User directory',
        sub: 'Roles, companies and last activity.',
        cols: ['User', 'Role', 'Company', 'Last active', 'Status'],
        rows: [
          [{ v: 'Ayesha Rahman', meta: 'Client administrator' }, 'Client admin', 'Delta Textiles', '12 minutes ago', { p: 'Active' }],
          [{ v: 'Tanvir Ahmed', meta: 'Client administrator' }, 'Client admin', 'Orion Foods', '1 hour ago', { p: 'Active' }],
          [{ v: 'Farhana Islam', meta: 'Client administrator' }, 'Client admin', 'Ashford Apparels', 'Yesterday', { p: 'Active' }],
          [{ v: 'Rafiq Chowdhury', meta: 'Client user' }, 'Client user', 'Meridian Logistics', '2 days ago', { p: 'Suspended' }],
          [{ v: 'N. Saliheen', meta: 'Firm administrator' }, 'Firm admin', 'Saliheen & Associates', 'Online', { p: 'Active' }]
        ]
      },
      upcoming: {
        title: 'Access reviews',
        sub: 'Account and permission checks.',
        items: [
          ['16', 'Nov', 'Quarterly access review', 'Client accounts', 'Scheduled'],
          ['20', 'Nov', 'Role audit — firm team', 'Permissions', 'In progress'],
          ['27', 'Nov', 'Suspended account review', 'Meridian Logistics', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent user activity',
        items: [
          ['Meridian Logistics invited two users', '2 days ago · User management'],
          ['Ayesha Rahman signed in', '12 minutes ago · Delta Textiles'],
          ['Account suspended — access review', 'Yesterday · Meridian Logistics'],
          ['Firm team role updated', '4 days ago · Administration']
        ]
      }
    },
    messages: {
      title: 'Messages & support',
      eyebrow: 'Client communication',
      sub: 'Client conversations, support threads and response progress.',
      stats: [
        ['Open threads', '14', 'across clients', 'i-msg'],
        ['Unread', '5', 'awaiting reply', 'i-bell'],
        ['Average reply', '2.4 hours', 'this week', 'i-history'],
        ['Resolved this week', '11', 'threads closed', 'i-check']
      ],
      table: {
        title: 'Support inbox',
        sub: 'Latest conversations and ownership.',
        cols: ['Thread', 'From', 'Topic', 'Updated', 'Status'],
        rows: [
          [{ v: 'Board pack question', meta: 'Delta Textiles' }, 'Ayesha Rahman', 'Governance', '12 minutes ago', { p: 'Unread' }],
          [{ v: 'VAT documents', meta: 'Orion Foods' }, 'Tanvir Ahmed', 'Compliance', '1 hour ago', { p: 'In progress' }],
          [{ v: 'Contract clause', meta: 'Ashford Apparels' }, 'Farhana Islam', 'Advisory', '3 hours ago', { p: 'Pending' }],
          [{ v: 'Access request', meta: 'Meridian Logistics' }, 'Rafiq Chowdhury', 'Account', 'Yesterday', { p: 'Resolved' }],
          [{ v: 'Hearing preparation', meta: 'Greenline Group' }, 'Nusrat Jahan', 'Disputes', '2 days ago', { p: 'Open' }]
        ]
      },
      upcoming: {
        title: 'Support schedule',
        sub: 'Planned communication.',
        items: [
          ['13', 'Nov', 'Client office hours', 'Open support slot', 'Scheduled'],
          ['18', 'Nov', 'Onboarding follow-up', 'Ashford Apparels', 'Scheduled'],
          ['25', 'Nov', 'Quarterly check-in', 'Delta Textiles', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent message activity',
        items: [
          ['Board pack question received', '12 minutes ago · Delta Textiles'],
          ['VAT documents acknowledged', '1 hour ago · Orion Foods'],
          ['Contract clause reply sent', '3 hours ago · Ashford Apparels'],
          ['Access request resolved', 'Yesterday · Meridian Logistics']
        ]
      }
    },
    notifications: {
      title: 'Notifications',
      eyebrow: 'Portal messaging',
      sub: 'Client notifications, delivery channels and scheduled sends.',
      stats: [
        ['Sent this month', '1,248', 'across channels', 'i-bell'],
        ['Unread in portal', '5', 'client accounts', 'i-msg'],
        ['Scheduled', '3', 'upcoming sends', 'i-calendar'],
        ['Failed', '0', 'this month', 'i-check']
      ],
      table: {
        title: 'Notification log',
        sub: 'Recent and scheduled notifications.',
        cols: ['Notification', 'Audience', 'Channel', 'Sent', 'Status'],
        rows: [
          [{ v: 'Quarter-end compliance reminder', meta: 'Internal target' }, 'All clients', 'Email + portal', '18 Nov 2026', { p: 'Scheduled' }],
          [{ v: 'Board pack ready', meta: 'Governance' }, 'Selected companies', 'Portal', '14 Nov 2026', { p: 'Delivered' }],
          [{ v: 'Document shared', meta: 'Document library' }, 'Individual clients', 'Email', '13 Nov 2026', { p: 'Delivered' }],
          [{ v: 'Package renewal notice', meta: 'Commercial' }, 'Selected clients', 'Email', '12 Nov 2026', { p: 'Scheduled' }],
          [{ v: 'Access change alert', meta: 'Account' }, 'Firm team', 'Portal', '11 Nov 2026', { p: 'Read' }]
        ]
      },
      upcoming: {
        title: 'Send schedule',
        sub: 'Queued notifications.',
        items: [
          ['12', 'Nov', 'Package renewal notice', 'Selected clients', 'Scheduled'],
          ['14', 'Nov', 'Board pack ready', 'Selected companies', 'Scheduled'],
          ['18', 'Nov', 'Quarter-end compliance reminder', 'All clients', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent notification activity',
        items: [
          ['Board pack notice delivered', 'Yesterday · Selected companies'],
          ['Document shared notification sent', '2 days ago · Individual clients'],
          ['Access alert read', '3 days ago · Firm team'],
          ['Delivery report refreshed', '5 days ago · Portal']
        ]
      }
    },
    audit: {
      title: 'Audit logs',
      eyebrow: 'Activity trail',
      sub: 'Recorded portal events, privileged actions and flagged activity.',
      stats: [
        ['Events logged', '8,420', 'this year', 'i-history'],
        ['Today', '76', 'recorded events', 'i-chart'],
        ['Privileged actions', '12', 'today', 'i-lock'],
        ['Flagged', '1', 'needs review', 'i-bell']
      ],
      table: {
        title: 'Audit trail',
        sub: 'Latest recorded events.',
        cols: ['Event', 'Actor', 'Area', 'Time', 'Status'],
        rows: [
          [{ v: 'Client record created', meta: 'Ashford Apparels' }, 'N. Saliheen', 'Client management', '09:42', { p: 'Completed' }],
          [{ v: 'Document uploaded', meta: 'AGM minutes' }, 'Ayesha Rahman', 'Document management', '09:30', { p: 'Completed' }],
          [{ v: 'Package updated', meta: 'Orion Foods' }, 'T. Chowdhury', 'Commercial', 'Yesterday', { p: 'Completed' }],
          [{ v: 'User suspended', meta: 'Access review' }, 'N. Saliheen', 'User management', 'Yesterday', { p: 'Flagged' }],
          [{ v: 'Report generated', meta: 'Q3 compliance' }, 'S. Karim', 'Reporting', '2 days ago', { p: 'Completed' }]
        ]
      },
      upcoming: {
        title: 'Audit reviews',
        sub: 'Scheduled checks.',
        items: [
          ['15', 'Nov', 'Weekly audit review', 'Recorded events', 'Scheduled'],
          ['22', 'Nov', 'Privileged action check', 'Firm team', 'Scheduled'],
          ['01', 'Dec', 'Log archive export', 'Quarter close', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent audit activity',
        items: [
          ['Client record created', 'Today · N. Saliheen'],
          ['Document uploaded', 'Today · Ayesha Rahman'],
          ['Package updated', 'Yesterday · T. Chowdhury'],
          ['User action flagged', 'Yesterday · Access review']
        ]
      }
    },
    settings: {
      title: 'Settings',
      eyebrow: 'Portal configuration',
      sub: 'Firm-level portal preferences, templates and workflow controls.',
      stats: [
        ['Active settings', '32', 'configured', 'i-cog'],
        ['Firm admins', '3', 'with access', 'i-id'],
        ['Templates', '14', 'notification and document', 'i-doc'],
        ['Pending changes', '2', 'awaiting approval', 'i-history']
      ],
      table: {
        title: 'Portal settings',
        sub: 'Current configuration values.',
        cols: ['Setting', 'Scope', 'Value', 'Status'],
        rows: [
          [{ v: 'Client onboarding checklist', meta: 'Workflow' }, 'Client management', 'Enabled', { p: 'Active' }],
          [{ v: 'Weekly digest', meta: 'Notification template' }, 'Notifications', 'Monday, 08:00', { p: 'Active' }],
          [{ v: 'Document categories', meta: 'Library structure' }, 'Documents', '8 categories', { p: 'Active' }],
          [{ v: 'Package change approval', meta: 'Commercial control' }, 'Commercial', 'Firm administrator', { p: 'Active' }],
          [{ v: 'Portal demo mode', meta: 'Design preview' }, 'Portal', 'Enabled', { p: 'Active' }]
        ]
      },
      upcoming: {
        title: 'Configuration reviews',
        sub: 'Planned settings checks.',
        items: [
          ['20', 'Nov', 'Notification preference review', 'Templates', 'Scheduled'],
          ['27', 'Nov', 'Workflow settings review', 'Onboarding', 'Scheduled'],
          ['04', 'Dec', 'Quarterly configuration check', 'Firm administrators', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent settings activity',
        items: [
          ['Weekly digest template updated', '2 days ago · Notifications'],
          ['Onboarding checklist revised', '4 days ago · Workflow'],
          ['Firm administrator added', '1 week ago · Administration'],
          ['Document category renamed', '1 week ago · Documents']
        ]
      }
    }
  };

  const CLIENT = {
    company: {
      title: 'My company profile',
      eyebrow: 'Delta Textiles Ltd.',
      sub: 'Registered company details, directors and profile verification.',
      stats: [
        ['Client since', '2024', 'Standard package', 'i-calendar'],
        ['Directors', '5', 'on record', 'i-bank'],
        ['Profile status', 'Verified', 'company details', 'i-check'],
        ['Documents', '28', 'in your library', 'i-folder']
      ],
      table: {
        title: 'Directors and officers',
        sub: 'Current appointments on record.',
        cols: ['Director', 'Role', 'Appointed', 'Status'],
        rows: [
          [{ v: 'Ayesha Rahman', meta: 'Client administrator' }, 'Managing director', '2024', { p: 'Current' }],
          [{ v: 'Imran Rahman', meta: 'Director' }, 'Director', '2024', { p: 'Current' }],
          [{ v: 'Shirin Akter', meta: 'Independent director' }, 'Independent director', '2025', { p: 'Current' }],
          [{ v: 'Mahbub Alam', meta: 'Company secretary' }, 'Company secretary', '2024', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'Profile reviews',
        sub: 'Upcoming company record checks.',
        items: [
          ['14', 'Nov', 'Annual profile review', 'Company details', 'Scheduled'],
          ['21', 'Nov', 'Director record check', 'Appointments', 'Scheduled'],
          ['28', 'Nov', 'Document expiry review', 'Trade licence file', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent profile activity',
        items: [
          ['Company profile viewed by adviser', '1 hour ago · Governance'],
          ['Director records confirmed', 'Yesterday · Company profile'],
          ['Trade licence file updated', '3 days ago · Documents'],
          ['Profile verification completed', '1 week ago · Onboarding']
        ]
      }
    },
    matters: {
      title: 'My legal matters',
      eyebrow: 'Matter workspace',
      sub: 'Your active legal matters, next steps and progress.',
      stats: [
        ['Active matters', '3', 'in progress', 'i-scale'],
        ['Awaiting your input', '1', 'needs a response', 'i-msg'],
        ['Next step this month', '2', 'scheduled', 'i-calendar'],
        ['Closed matters', '4', 'completed', 'i-check']
      ],
      table: {
        title: 'Matter overview',
        sub: 'Current matters and next actions.',
        cols: ['Matter', 'Reference', 'Type', 'Next step', 'Status'],
        rows: [
          [{ v: 'Share transfer — family holding', meta: 'Corporate' }, 'MAT-2026-014', 'Corporate', 'Board approval', { p: 'In progress' }],
          [{ v: 'Supplier contract review', meta: 'Commercial' }, 'MAT-2026-019', 'Commercial', 'Draft comments', { p: 'Awaiting input' }],
          [{ v: 'Employment policy update', meta: 'Labour' }, 'MAT-2026-021', 'Labour', 'Review call', { p: 'Scheduled' }],
          [{ v: 'Trademark renewal file', meta: 'Intellectual property' }, 'MAT-2025-008', 'IP', 'Archived', { p: 'Completed' }]
        ]
      },
      upcoming: {
        title: 'Next steps',
        sub: 'Where your input is needed.',
        items: [
          ['12', 'Nov', 'Supplier contract comments', 'Draft review', 'Awaiting input'],
          ['19', 'Nov', 'Employment policy call', 'Review meeting', 'Scheduled'],
          ['26', 'Nov', 'Share transfer board step', 'Approval preparation', 'In progress']
        ]
      },
      activity: {
        title: 'Recent matter activity',
        items: [
          ['Contract draft shared with you', '2 hours ago · Supplier contract'],
          ['Board approval step updated', 'Yesterday · Share transfer'],
          ['Employment policy call scheduled', '3 days ago · Labour'],
          ['Trademark file archived', '2 weeks ago · IP']
        ]
      }
    },
    governance: {
      title: 'Corporate governance',
      eyebrow: 'Board cycle 2026',
      sub: 'Board meetings, resolutions and governance actions for your company.',
      stats: [
        ['Board cycle', '2026', 'current', 'i-calendar'],
        ['Meetings held', '5', 'this cycle', 'i-bank'],
        ['Open actions', '3', 'assigned', 'i-check'],
        ['Next meeting', '19 Nov', 'board session', 'i-calendar']
      ],
      table: {
        title: 'Governance items',
        sub: 'Current board cycle actions.',
        cols: ['Item', 'Board cycle', 'Owner', 'Due', 'Status'],
        rows: [
          [{ v: 'Q4 board pack', meta: 'Governance' }, '2026', 'Company secretary', '14 Nov', { p: 'In progress' }],
          [{ v: 'AGM agenda', meta: 'Governance' }, '2026', 'Managing director', '21 Nov', { p: 'Draft' }],
          [{ v: 'Director disclosure', meta: 'Compliance' }, '2026', 'Ayesha Rahman', '18 Nov', { p: 'Awaiting input' }],
          [{ v: 'Governance action log', meta: 'Ongoing' }, '2026', 'Company secretary', 'Ongoing', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'Board calendar',
        sub: 'Upcoming governance dates.',
        items: [
          ['14', 'Nov', 'Q4 board pack review', 'Company secretary', 'In progress'],
          ['19', 'Nov', 'Board meeting', 'Delta Textiles', 'Scheduled'],
          ['21', 'Nov', 'AGM agenda draft', 'Managing director', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent governance activity',
        items: [
          ['Board minutes uploaded', '12 minutes ago · Governance'],
          ['Director disclosure requested', 'Yesterday · Compliance'],
          ['Action log updated', '3 days ago · Governance'],
          ['Board pack outline shared', '5 days ago · Governance']
        ]
      }
    },
    calendar: {
      title: 'Compliance calendar',
      eyebrow: 'Your working calendar',
      sub: 'Upcoming compliance and governance items for Delta Textiles Ltd.',
      stats: [
        ['Next 30 days', '4', 'scheduled items', 'i-calendar'],
        ['Completed this quarter', '11', 'items closed', 'i-check'],
        ['Needs your input', '2', 'open items', 'i-msg'],
        ['Owners assigned', '6', 'across items', 'i-briefcase']
      ],
      table: {
        title: 'Calendar items',
        sub: 'Internal working windows, not statutory deadlines.',
        cols: ['Item', 'Owner', 'Window', 'Status'],
        rows: [
          [{ v: 'Annual return pack', meta: 'Compliance' }, 'Company secretary', 'This month', { p: 'In progress' }],
          [{ v: 'Board meeting pack', meta: 'Governance' }, 'Company secretary', '14 Nov', { p: 'In progress' }],
          [{ v: 'VAT cycle review', meta: 'Finance coordination' }, 'Finance lead', 'This week', { p: 'Scheduled' }],
          [{ v: 'Labour register update', meta: 'HR governance' }, 'HR lead', 'Next 30 days', { p: 'Awaiting input' }],
          [{ v: 'Trade licence file', meta: 'Corporate records' }, 'Operations lead', 'Quarter end', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'Next checkpoints',
        sub: 'Dates to prepare for.',
        items: [
          ['14', 'Nov', 'Board meeting pack', 'Company secretary', 'In progress'],
          ['18', 'Nov', 'Annual return draft', 'Company secretary', 'Scheduled'],
          ['25', 'Nov', 'Labour register review', 'HR lead', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent calendar activity',
        items: [
          ['Board pack item updated', '2 hours ago · Governance'],
          ['VAT review scheduled', 'Yesterday · Finance'],
          ['Labour register assigned', '3 days ago · HR'],
          ['Trade licence file confirmed', '1 week ago · Operations']
        ]
      }
    },
    cyber: {
      title: 'Cyber risk management',
      eyebrow: 'Cyber risk',
      sub: 'Cyber controls, policy currency and open actions for your company.',
      stats: [
        ['Controls tracked', '12', 'for your company', 'i-lock'],
        ['Current', '9', 'policies and controls', 'i-check'],
        ['Reviews due', '2', 'next 60 days', 'i-calendar'],
        ['Open actions', '1', 'assigned', 'i-shield']
      ],
      table: {
        title: 'Cyber control register',
        sub: 'Review currency and status.',
        cols: ['Control', 'Standard', 'Last review', 'Status'],
        rows: [
          [{ v: 'Access control', meta: 'Internal policy' }, 'Policy set', 'Sep 2026', { p: 'Current' }],
          [{ v: 'Data backup', meta: 'Operational resilience' }, 'Policy set', 'Oct 2026', { p: 'Current' }],
          [{ v: 'Vendor register', meta: 'Third-party oversight' }, 'Policy set', 'Aug 2026', { p: 'Review due' }],
          [{ v: 'Incident response', meta: 'Response readiness' }, 'Policy set', 'Jul 2026', { p: 'In progress' }],
          [{ v: 'Retention schedule', meta: 'Records management' }, 'Policy set', '—', { p: 'Not started' }]
        ]
      },
      upcoming: {
        title: 'Cyber reviews',
        sub: 'Upcoming checks with your adviser.',
        items: [
          ['12', 'Nov', 'Incident response walkthrough', 'With Saliheen & Associates', 'In progress'],
          ['20', 'Nov', 'Vendor register review', 'Third-party oversight', 'Scheduled'],
          ['27', 'Nov', 'Retention schedule workshop', 'Records management', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent cyber activity',
        items: [
          ['Cyber risk register updated', '5 hours ago · Documents'],
          ['Access control evidence added', 'Yesterday · Cyber'],
          ['Vendor review noted', '4 days ago · Cyber'],
          ['Backup test record filed', '1 week ago · Cyber']
        ]
      }
    },
    labour: {
      title: 'Labor & HR compliance',
      eyebrow: 'People compliance',
      sub: 'HR policies, employee matters and review status for your company.',
      stats: [
        ['Policies', '8', 'on record', 'i-briefcase'],
        ['Current', '6', 'up to date', 'i-check'],
        ['Reviews due', '2', 'next 60 days', 'i-calendar'],
        ['Open employee matters', '1', 'with your adviser', 'i-msg']
      ],
      table: {
        title: 'HR policy register',
        sub: 'Policy review status.',
        cols: ['Policy', 'Requirement', 'Last updated', 'Status'],
        rows: [
          [{ v: 'Employment policy', meta: 'Handbook' }, 'Internal handbook', 'Oct 2026', { p: 'Current' }],
          [{ v: 'Grievance procedure', meta: 'Handbook' }, 'Internal handbook', 'Sep 2026', { p: 'Current' }],
          [{ v: 'Working hours', meta: 'Handbook' }, 'Internal handbook', '—', { p: 'Review due' }],
          [{ v: 'Separation checklist', meta: 'HR operations' }, 'Internal handbook', 'Aug 2026', { p: 'In review' }],
          [{ v: 'Safety protocol', meta: 'Workplace' }, 'Internal handbook', 'Oct 2026', { p: 'Current' }]
        ]
      },
      upcoming: {
        title: 'HR reviews',
        sub: 'Upcoming policy checkpoints.',
        items: [
          ['13', 'Nov', 'Employment policy review', 'Draft comments', 'In progress'],
          ['20', 'Nov', 'Grievance procedure review', 'Annual check', 'Scheduled'],
          ['27', 'Nov', 'HR clinic', 'Employee matters', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent HR activity',
        items: [
          ['Employment policy draft shared', '2 hours ago · Documents'],
          ['Grievance log reviewed', 'Yesterday · HR'],
          ['Separation checklist updated', '4 days ago · HR'],
          ['Safety protocol filed', '1 week ago · HR']
        ]
      }
    },
    board: {
      title: 'Board decision support',
      eyebrow: 'Board support',
      sub: 'Your upcoming board meeting, resolutions and preparation items.',
      stats: [
        ['Next meeting', '19 Nov', 'board session', 'i-calendar'],
        ['Open resolutions', '3', 'in preparation', 'i-doc'],
        ['Board pack items', '6', 'for review', 'i-folder'],
        ['Actions due', '2', 'this week', 'i-check']
      ],
      table: {
        title: 'Resolution tracker',
        sub: 'Board decisions in preparation.',
        cols: ['Resolution', 'Meeting', 'Deadline', 'Status'],
        rows: [
          [{ v: 'Q4 budget approval', meta: 'Finance' }, '19 Nov 2026', '14 Nov 2026', { p: 'In progress' }],
          [{ v: 'Director appointment', meta: 'Governance' }, '19 Nov 2026', '14 Nov 2026', { p: 'Draft' }],
          [{ v: 'Related-party disclosure', meta: 'Compliance' }, '19 Nov 2026', '18 Nov 2026', { p: 'Awaiting input' }],
          [{ v: 'AGM agenda', meta: 'Governance' }, '02 Dec 2026', '21 Nov 2026', { p: 'Scheduled' }]
        ]
      },
      upcoming: {
        title: 'Board preparation',
        sub: 'Items before the next meeting.',
        items: [
          ['14', 'Nov', 'Board pack comments', 'Company secretary', 'In progress'],
          ['18', 'Nov', 'Director disclosure', 'Ayesha Rahman', 'Awaiting input'],
          ['19', 'Nov', 'Board meeting', 'Delta Textiles', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent board activity',
        items: [
          ['Board pack updated', '1 hour ago · Governance'],
          ['Director disclosure requested', 'Yesterday · Compliance'],
          ['Budget note shared', '3 days ago · Finance'],
          ['Meeting agenda confirmed', '1 week ago · Governance']
        ]
      }
    },
    adr: {
      title: 'Disputes & ADR',
      eyebrow: 'Dispute support',
      sub: 'Open disputes, negotiation steps and alternative dispute resolution.',
      stats: [
        ['Open disputes', '1', 'active file', 'i-gavel'],
        ['In mediation', '1', 'session planned', 'i-msg'],
        ['Resolved', '1', 'closed matter', 'i-check'],
        ['External forums', '2', 'available options', 'i-bank']
      ],
      table: {
        title: 'Dispute register',
        sub: 'Current files and next steps.',
        cols: ['Dispute', 'Counterparty', 'Forum', 'Status'],
        rows: [
          [{ v: 'Supplier payment query', meta: 'ADR-2026-004' }, 'Northline Traders', 'Negotiation', { p: 'In progress' }],
          [{ v: 'Delivery delay claim', meta: 'ADR-2026-003' }, 'Coastal Freight', 'Mediation', { p: 'Completed' }],
          [{ v: 'Service agreement issue', meta: 'ADR-2026-005' }, 'Apex Services', 'Negotiation', { p: 'Under review' }]
        ]
      },
      upcoming: {
        title: 'Dispute milestones',
        sub: 'Upcoming responses and sessions.',
        items: [
          ['13', 'Nov', 'Position paper — Apex Services', 'Internal review', 'In progress'],
          ['18', 'Nov', 'Response draft — Northline Traders', 'Negotiation', 'Scheduled'],
          ['26', 'Nov', 'Mediation follow-up', 'Coastal Freight', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent dispute activity',
        items: [
          ['Position paper draft shared', '3 hours ago · Apex Services'],
          ['Correspondence received', 'Yesterday · Northline Traders'],
          ['Mediation closed', '2 weeks ago · Coastal Freight'],
          ['Dispute register updated', '3 weeks ago · ADR']
        ]
      }
    },
    advisory: {
      title: 'Legal & financial advisory',
      eyebrow: 'Advisory desk',
      sub: 'Your advisory requests, assigned adviser and delivery progress.',
      stats: [
        ['Open requests', '2', 'with the advisory desk', 'i-msg'],
        ['Resolved this quarter', '5', 'requests closed', 'i-check'],
        ['Average response', '1.2 days', 'this quarter', 'i-calendar'],
        ['Adviser assigned', '1', 'dedicated contact', 'i-briefcase']
      ],
      table: {
        title: 'Advisory requests',
        sub: 'Current requests and ownership.',
        cols: ['Request', 'Area', 'Adviser', 'Raised', 'Status'],
        rows: [
          [{ v: 'Expansion advisory', meta: 'New market entry' }, 'Corporate', 'N. Saliheen', '06 Nov 2026', { p: 'In progress' }],
          [{ v: 'Supplier clause query', meta: 'Supply agreement' }, 'Commercial', 'S. Karim', '03 Nov 2026', { p: 'Under review' }],
          [{ v: 'Board succession note', meta: 'Governance' }, 'Governance', 'N. Saliheen', '24 Oct 2026', { p: 'Completed' }]
        ]
      },
      upcoming: {
        title: 'Advisory schedule',
        sub: 'Calls and checkpoints.',
        items: [
          ['12', 'Nov', 'Expansion advisory call', 'N. Saliheen', 'Scheduled'],
          ['19', 'Nov', 'Supplier clause follow-up', 'S. Karim', 'Scheduled'],
          ['26', 'Nov', 'Quarterly advisory review', 'Advisory desk', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent advisory activity',
        items: [
          ['Expansion advisory note shared', '1 hour ago · N. Saliheen'],
          ['Supplier clause query reviewed', 'Yesterday · S. Karim'],
          ['Board succession note delivered', '2 weeks ago · Governance'],
          ['Advisory request opened', '3 weeks ago · Corporate']
        ]
      }
    },
    documents: {
      title: 'Documents & downloads',
      eyebrow: 'Your document library',
      sub: 'Documents shared with Delta Textiles Ltd. and items awaiting your review.',
      stats: [
        ['Available documents', '28', 'in your library', 'i-folder'],
        ['Shared this week', '3', 'new documents', 'i-doc'],
        ['Awaiting your review', '2', 'open items', 'i-check'],
        ['Categories', '8', 'document groups', 'i-folder']
      ],
      table: {
        title: 'Recent documents',
        sub: 'Latest files and their status.',
        cols: ['Document', 'Category', 'Updated', 'Status'],
        rows: [
          [{ v: 'Q3 compliance report', meta: 'PDF · 12 pages' }, 'Compliance', 'Nov 2026', { p: 'New' }],
          [{ v: 'Board minutes — Oct', meta: 'PDF · Governance' }, 'Governance', 'Oct 2026', { p: 'Read' }],
          [{ v: 'Employment policy — draft', meta: 'DOCX · Labour' }, 'Labour', 'Oct 2026', { p: 'Awaiting input' }],
          [{ v: 'Trade licence — current', meta: 'PDF · Corporate' }, 'Corporate', 'Oct 2026', { p: 'Current' }],
          [{ v: 'Supplier contract — template', meta: 'DOCX · Commercial' }, 'Commercial', 'Sep 2026', { p: 'Read' }]
        ]
      },
      upcoming: {
        title: 'Review requests',
        sub: 'Items awaiting your attention.',
        items: [
          ['13', 'Nov', 'Employment policy draft', 'Labour', 'Awaiting input'],
          ['18', 'Nov', 'Q3 compliance report', 'Compliance', 'In progress'],
          ['25', 'Nov', 'Board pack — Q4', 'Governance', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent document activity',
        items: [
          ['Q3 compliance report shared', '2 hours ago · Compliance'],
          ['Board minutes uploaded', 'Yesterday · Governance'],
          ['Employment policy draft shared', '3 days ago · Labour'],
          ['Trade licence file updated', '1 week ago · Corporate']
        ]
      }
    },
    reports: {
      title: 'Reports',
      eyebrow: 'Reporting',
      sub: 'Reports prepared for your company by Saliheen & Associates.',
      stats: [
        ['Available reports', '12', 'in your library', 'i-chart'],
        ['Latest report', 'Q3 2026', 'compliance cycle', 'i-doc'],
        ['Shared this quarter', '4', 'new reports', 'i-folder'],
        ['Scheduled', '1', 'upcoming report', 'i-calendar']
      ],
      table: {
        title: 'Report library',
        sub: 'Recent reports and delivery status.',
        cols: ['Report', 'Period', 'Prepared', 'Status'],
        rows: [
          [{ v: 'Quarterly compliance report', meta: 'PDF' }, 'Q3 2026', 'Saliheen & Associates', { p: 'New' }],
          [{ v: 'Corporate governance summary', meta: 'PDF' }, '2026', 'Saliheen & Associates', { p: 'Read' }],
          [{ v: 'Legal matter progress', meta: 'PDF' }, 'October 2026', 'Saliheen & Associates', { p: 'Read' }],
          [{ v: 'Cyber risk assessment', meta: 'PDF' }, 'September 2026', 'Saliheen & Associates', { p: 'Read' }],
          [{ v: 'Board effectiveness review', meta: 'Scheduled' }, '2026', 'Saliheen & Associates', { p: 'Scheduled' }]
        ]
      },
      upcoming: {
        title: 'Report cycle',
        sub: 'Upcoming reports for your company.',
        items: [
          ['02', 'Dec', 'Matter progress report', 'November cycle', 'Scheduled'],
          ['15', 'Dec', 'Governance summary', 'Year-end pack', 'Scheduled'],
          ['20', 'Dec', 'Compliance snapshot', 'Q4 2026', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent report activity',
        items: [
          ['Q3 compliance report shared', '2 hours ago · Compliance'],
          ['Governance summary viewed', 'Yesterday · Governance'],
          ['Matter progress report opened', '3 days ago · Matters'],
          ['Cyber assessment downloaded', '1 week ago · Cyber']
        ]
      }
    },
    requests: {
      title: 'Advisory requests',
      eyebrow: 'Request assistance',
      sub: 'Requests raised with your legal team and their progress.',
      stats: [
        ['Open requests', '2', 'in progress', 'i-msg'],
        ['Awaiting information', '1', 'needs your reply', 'i-check'],
        ['Resolved this quarter', '5', 'requests closed', 'i-check'],
        ['Average response', '1.2 days', 'this quarter', 'i-calendar']
      ],
      table: {
        title: 'Your requests',
        sub: 'Current and recent advisory requests.',
        cols: ['Request', 'Area', 'Raised', 'Status'],
        rows: [
          [{ v: 'Contract review — supplier', meta: 'Commercial' }, 'Commercial', '06 Nov 2026', { p: 'In progress' }],
          [{ v: 'Policy interpretation — leave', meta: 'Labour' }, 'Labour', '02 Nov 2026', { p: 'Awaiting input' }],
          [{ v: 'Board support — agenda', meta: 'Governance' }, 'Governance', '21 Oct 2026', { p: 'Completed' }],
          [{ v: 'Tax query — filing pack', meta: 'Financial' }, 'Financial', '14 Oct 2026', { p: 'Completed' }]
        ]
      },
      upcoming: {
        title: 'Request schedule',
        sub: 'Upcoming calls and responses.',
        items: [
          ['12', 'Nov', 'Supplier contract call', 'Commercial', 'Scheduled'],
          ['19', 'Nov', 'Leave policy response', 'Labour', 'In progress'],
          ['26', 'Nov', 'Quarterly advisory review', 'Advisory desk', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent request activity',
        items: [
          ['Supplier contract query acknowledged', '2 hours ago · Commercial'],
          ['Leave policy information requested', 'Yesterday · Labour'],
          ['Board agenda support completed', '2 weeks ago · Governance'],
          ['Tax query closed', '3 weeks ago · Financial']
        ]
      }
    },
    messages: {
      title: 'Messages & support',
      eyebrow: 'Your legal team',
      sub: 'Conversations with Saliheen & Associates and support updates.',
      stats: [
        ['Open threads', '3', 'in progress', 'i-msg'],
        ['Unread', '1', 'awaiting your reply', 'i-bell'],
        ['Team response', '2.4 hours', 'average this week', 'i-calendar'],
        ['Resolved this month', '6', 'threads closed', 'i-check']
      ],
      table: {
        title: 'Conversations',
        sub: 'Recent threads and participants.',
        cols: ['Thread', 'Participants', 'Topic', 'Updated', 'Status'],
        rows: [
          [{ v: 'Board pack question', meta: 'Delta Textiles' }, 'N. Saliheen', 'Governance', '12 minutes ago', { p: 'Unread' }],
          [{ v: 'VAT documents', meta: 'Delta Textiles' }, 'T. Chowdhury', 'Compliance', '1 hour ago', { p: 'In progress' }],
          [{ v: 'Contract clause', meta: 'Delta Textiles' }, 'S. Karim', 'Advisory', 'Yesterday', { p: 'Awaiting reply' }],
          [{ v: 'Access request', meta: 'Delta Textiles' }, 'Support team', 'Account', '2 days ago', { p: 'Resolved' }]
        ]
      },
      upcoming: {
        title: 'Support schedule',
        sub: 'Planned check-ins.',
        items: [
          ['13', 'Nov', 'Client office hours', 'Open support slot', 'Scheduled'],
          ['18', 'Nov', 'Board prep check-in', 'N. Saliheen', 'Scheduled'],
          ['25', 'Nov', 'Quarterly review', 'Advisory desk', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent message activity',
        items: [
          ['Board pack question received', '12 minutes ago · N. Saliheen'],
          ['VAT documents acknowledged', '1 hour ago · T. Chowdhury'],
          ['Contract clause reply sent', 'Yesterday · S. Karim'],
          ['Access request resolved', '2 days ago · Support']
        ]
      }
    },
    notifications: {
      title: 'Notifications',
      eyebrow: 'Your updates',
      sub: 'Portal alerts, document updates and reminders for your company.',
      stats: [
        ['Unread', '1', 'needs attention', 'i-bell'],
        ['This month', '18', 'notifications', 'i-msg'],
        ['Portal alerts', '12', 'in-app', 'i-bell'],
        ['Document updates', '6', 'shared with you', 'i-folder']
      ],
      table: {
        title: 'Notification history',
        sub: 'Recent updates and their status.',
        cols: ['Notification', 'Channel', 'Received', 'Status'],
        rows: [
          [{ v: 'Q3 compliance report ready', meta: 'Compliance' }, 'Portal', 'Today', { p: 'Unread' }],
          [{ v: 'Board meeting reminder', meta: 'Governance' }, 'Email + portal', 'Yesterday', { p: 'Read' }],
          [{ v: 'Document shared — employment policy', meta: 'Labour' }, 'Portal', '2 days ago', { p: 'Read' }],
          [{ v: 'Access confirmation', meta: 'Account' }, 'Email', '3 days ago', { p: 'Read' }],
          [{ v: 'Package renewal note', meta: 'Commercial' }, 'Email', 'Last week', { p: 'Read' }]
        ]
      },
      upcoming: {
        title: 'Upcoming notices',
        sub: 'Scheduled updates.',
        items: [
          ['14', 'Nov', 'Board pack reminder', 'Governance', 'Scheduled'],
          ['18', 'Nov', 'Compliance checkpoint', 'Compliance', 'Scheduled'],
          ['25', 'Nov', 'Quarterly review notice', 'Advisory desk', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent notification activity',
        items: [
          ['Q3 report notification received', 'Today · Compliance'],
          ['Board reminder sent', 'Yesterday · Governance'],
          ['Document update alert', '2 days ago · Labour'],
          ['Access confirmation sent', '3 days ago · Account']
        ]
      }
    },
    subscription: {
      title: 'My subscription',
      eyebrow: 'Standard package',
      sub: 'Your package entitlements, active users and renewal cycle.',
      stats: [
        ['Current plan', 'Standard', 'governance support', 'i-package'],
        ['Renewal', 'Nov 2026', 'annual cycle', 'i-calendar'],
        ['Included modules', '6', 'active', 'i-check'],
        ['Active users', '3', 'portal accounts', 'i-bank']
      ],
      table: {
        title: 'Package entitlements',
        sub: 'What is included in your current package.',
        cols: ['Entitlement', 'Included', 'Used', 'Status'],
        rows: [
          [{ v: 'Governance workspace', meta: 'Board cycles' }, 'Included', 'Active', { p: 'Active' }],
          [{ v: 'Compliance calendar', meta: 'Working calendar' }, 'Included', 'Active', { p: 'Active' }],
          [{ v: 'Document library', meta: 'Shared files' }, 'Included', '28 documents', { p: 'Active' }],
          [{ v: 'Advisory requests', meta: 'Advisory desk' }, 'Included', '2 open', { p: 'Active' }],
          [{ v: 'Board decision support', meta: 'Optional module' }, 'Optional', 'Not active', { p: 'Not active' }],
          [{ v: 'Dedicated adviser', meta: 'Named contact' }, 'Included', 'Assigned', { p: 'Included' }]
        ]
      },
      upcoming: {
        title: 'Renewal cycle',
        sub: 'Plan checkpoints.',
        items: [
          ['30', 'Nov', 'Annual package review', 'Standard plan', 'Scheduled'],
          ['05', 'Dec', 'Module review', 'Optional services', 'Scheduled'],
          ['15', 'Dec', 'Account check-in', 'Advisory desk', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent subscription activity',
        items: [
          ['Standard package confirmed', '1 week ago · Commercial'],
          ['Third user added', '2 weeks ago · Account'],
          ['Document library usage updated', '3 weeks ago · Portal'],
          ['Annual review scheduled', '1 month ago · Commercial']
        ]
      }
    },
    settings: {
      title: 'Account settings',
      eyebrow: 'Your preferences',
      sub: 'Company contact details, notification preferences and portal access.',
      stats: [
        ['Active preferences', '8', 'configured', 'i-cog'],
        ['Notification channels', '2', 'email and portal', 'i-bell'],
        ['Users', '3', 'portal accounts', 'i-bank'],
        ['Last review', 'Oct 2026', 'account check', 'i-check']
      ],
      table: {
        title: 'Account preferences',
        sub: 'Current settings for your company.',
        cols: ['Setting', 'Group', 'Value', 'Status'],
        rows: [
          [{ v: 'Company profile', meta: 'Registered details' }, 'Organisation', 'Verified', { p: 'Current' }],
          [{ v: 'Primary contact', meta: 'Account owner' }, 'Organisation', 'Ayesha Rahman', { p: 'Current' }],
          [{ v: 'Email notifications', meta: 'Delivery' }, 'Notifications', 'Enabled', { p: 'Active' }],
          [{ v: 'Portal alerts', meta: 'Delivery' }, 'Notifications', 'Enabled', { p: 'Active' }],
          [{ v: 'Document sharing', meta: 'Access' }, 'Access', 'Company users', { p: 'Active' }],
          [{ v: 'Weekly digest', meta: 'Delivery' }, 'Notifications', 'Monday', { p: 'Active' }]
        ]
      },
      upcoming: {
        title: 'Preference review',
        sub: 'Planned account checks.',
        items: [
          ['20', 'Nov', 'Notification preference review', 'Email and portal', 'Scheduled'],
          ['27', 'Nov', 'User access check', '3 accounts', 'Scheduled'],
          ['04', 'Dec', 'Company details review', 'Registered profile', 'Scheduled']
        ]
      },
      activity: {
        title: 'Recent settings activity',
        items: [
          ['Weekly digest enabled', '3 days ago · Notifications'],
          ['Document sharing updated', '1 week ago · Access'],
          ['Primary contact confirmed', '3 weeks ago · Organisation'],
          ['User access reviewed', '1 month ago · Account']
        ]
      }
    }
  };

  const VIEWS = role === 'admin' ? ADMIN : CLIENT;
  const labelMap = LABELS[role];

  const esc = (value) =>
    String(value).replace(/[&<>"']/g, (ch) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ch]));

  const pill = (text) => {
    const tone = TONES[String(text).toLowerCase()] || 'neutral';
    return `<span class="pill ${tone}">${esc(text)}</span>`;
  };

  const cell = (value, first) => {
    if (value && typeof value === 'object') {
      if (value.p) return pill(value.p);
      const body = esc(value.v);
      return (first ? `<b>${body}</b>` : body) + (value.meta ? `<span class="meta">${esc(value.meta)}</span>` : '');
    }
    return first ? `<b>${esc(value)}</b>` : esc(value);
  };

  let currentId = 'home';

  const setActive = (id) => {
    links.forEach((link) => {
      const on = link.dataset.view === id;
      link.classList.toggle('active', on);
      if (on) link.setAttribute('aria-current', 'page');
      else link.removeAttribute('aria-current');
    });
  };

  const closeNav = () => {
    body.classList.remove('nav-open');
    if (menuBtn) menuBtn.setAttribute('aria-expanded', 'false');
  };

  const home = () => {
    moduleView.hidden = true;
    moduleView.innerHTML = '';
    homeView.hidden = false;
    if (crumb) crumb.textContent = homeCrumb;
    document.title = homeTitle;
    setActive('home');
    currentId = 'home';
  };

  const markup = (view, id) => {
    const exportHref = id === 'reports' ? '#documents' : '#reports';
    const exportLabel = id === 'reports' ? 'Document library' : 'Export view';
    const stats = view.stats.map((s) => `
      <article class="stat">
        <div class="stat-top"><span class="stat-label">${esc(s[0])}</span><span class="stat-icon"><svg viewBox="0 0 24 24"><use href="#${esc(s[3])}"/></svg></span></div>
        <strong>${esc(s[1])}</strong>
        <small>${esc(s[2])}</small>
      </article>`).join('');
    const head = view.table.cols.map((c) => `<th>${esc(c)}</th>`).join('');
    const rows = view.table.rows.map((row) => `<tr>${row.map((c, i) => `<td>${cell(c, i === 0)}</td>`).join('')}</tr>`).join('');
    const upcoming = view.upcoming.items.map((it) => `
      <li>
        <span class="when"><b>${esc(it[0])}</b><span>${esc(it[1])}</span></span>
        <span class="meta"><b>${esc(it[2])}</b><span>${esc(it[3])}</span></span>
        ${it[4] ? pill(it[4]) : ''}
      </li>`).join('');
    const feed = view.activity.items.map((a) => `<li><b>${esc(a[0])}</b><span>${esc(a[1])}</span></li>`).join('');

    return `
      <div class="page-head">
        <div>
          <p class="eyebrow">${esc(view.eyebrow)}</p>
          <h1>${esc(view.title)}</h1>
          <p class="sub">${esc(view.sub)}</p>
        </div>
      </div>
      <section class="stat-grid" aria-label="Module statistics">${stats}</section>
      <div class="grid-main">
        <section class="card">
          <div class="card-head">
            <div><h2>${esc(view.table.title)}</h2><p>${esc(view.table.sub)}</p></div>
            <a class="link" href="${exportHref}">${exportLabel}</a>
          </div>
          <div class="table-wrap">
            <table class="t"><thead><tr>${head}</tr></thead><tbody>${rows}</tbody></table>
          </div>
        </section>
        <div class="stack">
          <section class="card">
            <div class="card-head"><div><h2>${esc(view.upcoming.title)}</h2><p>${esc(view.upcoming.sub)}</p></div></div>
            <ul class="list">${upcoming}</ul>
          </section>
          <section class="card">
            <div class="card-head"><div><h2>${esc(view.activity.title)}</h2></div></div>
            <ul class="feed">${feed}</ul>
          </section>
        </div>
      </div>
      <p class="note">Sample data for design review — records will populate from the portal database once connected.</p>`;
  };

  const open = (id) => {
    const view = VIEWS[id];
    if (!view) return false;
    moduleView.innerHTML = markup(view, id);
    homeView.hidden = true;
    moduleView.hidden = false;
    const link = links.find((item) => item.dataset.view === id);
    const label = link ? link.querySelector('span').textContent : view.title;
    if (crumb) crumb.textContent = label;
    document.title = `${view.title} | Saliheen & Associates`;
    setActive(id);
    currentId = id;
    closeNav();
    window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
    return true;
  };

  let initial = true;
  const route = () => {
    const raw = location.hash.replace(/^#\/?/, '').toLowerCase();
    if (!raw || raw === 'home') {
      home();
    } else if (VIEWS[raw]) {
      if (raw !== currentId) open(raw);
    } else if (raw === 'main') {
      return;
    } else {
      home();
    }
    if (initial) initial = false;
  };

  links.forEach((link) => {
    const span = link.querySelector('span');
    if (!span) return;
    const key = span.textContent.replace(/\s+/g, ' ').trim().toLowerCase();
    const id = labelMap[key];
    if (!id || (id !== 'home' && !VIEWS[id])) return;
    link.dataset.view = id;
    link.setAttribute('href', `#${id}`);
  });

  window.addEventListener('hashchange', route);
  route();
})();
